<?php

namespace App\Services;

use App\Models\CustomerProfile;
use App\Models\VerificationDocument;
use App\Models\VerificationSession;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManager;
use Illuminate\Support\Str;
use Exception;
use Aws\Credentials\Credentials;
use Aws\Rekognition\RekognitionClient;
use Aws\Exception\AwsException;

class FacialComparisonService
{
    protected $omangApiService;
    protected $imageManager;
    protected $rekognition;
    protected $threshold;
    protected $maxImageSize; // 5MB in bytes
    protected $minImageDimension; // AWS requires at least 80x80 pixels
    protected $optimalImageDimension; // Optimal dimension for face comparison
    protected $maxRetries;

    public function __construct(OmangApiService $omangApiService, ImageManager $imageManager)
    {
        $this->omangApiService = $omangApiService;
        $this->imageManager = $imageManager;

        // Log Intervention Image version for debugging
        Log::info('Intervention Image version check', [
            'has_make_method' => method_exists($this->imageManager, 'make'),
            'has_read_method' => method_exists($this->imageManager, 'read'),
            'class' => get_class($this->imageManager),
        ]);

        // Configure comparison parameters
        $this->threshold = config('verification.facial_threshold', 70);
        $this->maxImageSize = 5 * 1024 * 1024; // 5MB
        $this->minImageDimension = 80; // 80px (AWS minimum)
        $this->optimalImageDimension = 1200; // Optimal for face detection
        $this->maxRetries = 2; // Max retries for API calls

        // Initialize AWS Rekognition client
        $this->initRekognition();
    }

    /**
     * Initialize AWS Rekognition client with enhanced error handling
     */
    private function initRekognition()
    {
        try {
            // Get credentials from .env
            $key = env('AWS_ACCESS_KEY_ID');
            $secret = env('AWS_SECRET_ACCESS_KEY');
            $region = env('AWS_DEFAULT_REGION', 'us-east-1');

            // Log credentials status (without exposing values)
            Log::info('AWS credentials check', [
                'key_exists' => !empty($key),
                'key_length' => $key ? strlen($key) : 0,
                'secret_exists' => !empty($secret),
                'secret_length' => $secret ? strlen($secret) : 0,
                'region' => $region,
            ]);

            if (empty($key) || empty($secret)) {
                throw new Exception('AWS credentials not properly configured');
            }

            // Create a credentials object
            $credentials = new Credentials($key, $secret);

            // Create the Rekognition client with improved timeout settings
            $this->rekognition = new RekognitionClient([
                'version' => 'latest',
                'region' => $region,
                'credentials' => $credentials,
                'http' => [
                    'connect_timeout' => 10,
                    'timeout' => 30,
                    'retry_max' => 3
                ]
            ]);

            Log::info('AWS Rekognition client initialized successfully');
        } catch (AwsException $e) {
            Log::error('Failed to initialize AWS Rekognition - AWS Exception', [
                'error' => $e->getMessage(),
                'aws_error_type' => $e->getAwsErrorType(),
                'aws_error_code' => $e->getAwsErrorCode(),
                'request_id' => $e->getAwsRequestId(),
                'status_code' => $e->getStatusCode(),
            ]);

            throw new Exception('Failed to initialize facial recognition service: ' . $e->getMessage());
        } catch (Exception $e) {
            Log::error('Failed to initialize AWS Rekognition - General Exception', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            throw new Exception('Failed to initialize facial recognition service: ' . $e->getMessage());
        }
    }

    /**
     * Compare a selfie with the Omang photo
     * 
     * @param CustomerProfile $customerProfile
     * @param string|UploadedFile $selfieImage - Can be a file upload, base64 string, or path
     * @return array
     */
    public function compareFaces(CustomerProfile $customerProfile, $selfieImage): array
    {
        try {
            Log::info('Starting facial comparison', [
                'customer_id' => $customerProfile->id,
            ]);

            // 1. Save the selfie image
            $selfiePhotoPath = $this->saveImage($selfieImage, 'selfies');
            if (!$selfiePhotoPath) {
                return [
                    'success' => false,
                    'message' => 'Failed to process selfie image',
                ];
            }

            // 2. Check if there's an uploaded Omang front image for this customer
            $omangFrontDocument = VerificationDocument::where('customer_profile_id', $customerProfile->id)
                ->where('document_type', 'omang_front')
                ->latest()
                ->first();

            if ($omangFrontDocument && Storage::disk('secure')->exists($omangFrontDocument->file_path)) {
                // Use the uploaded Omang card and extract the face
                Log::info('Using uploaded Omang card for facial comparison', [
                    'document_id' => $omangFrontDocument->id,
                    'file_path' => $omangFrontDocument->file_path,
                ]);

                $omangPhotoPath = $this->extractFaceFromOmangCard($omangFrontDocument->file_path);

                if (!$omangPhotoPath) {
                    return [
                        'success' => false,
                        'message' => 'Could not extract face from your Omang card. Please upload a clearer image.',
                    ];
                }
            } else {
                // Fallback to the API if no uploaded Omang card is found
                Log::info('No uploaded Omang card found, using API', [
                    'omang_number' => substr($customerProfile->omang_number, 0, 5) . '****',
                ]);

                // Get the Omang photo from ID system
                $omangResponse = $this->omangApiService->getOmangPhoto($customerProfile->omang_number);

                if (!isset($omangResponse['success']) || !$omangResponse['success']) {
                    return [
                        'success' => false,
                        'message' => 'Failed to retrieve Omang photo: ' . ($omangResponse['message'] ?? 'Unknown error'),
                    ];
                }

                // Save the Omang photo from base64
                $omangPhotoPath = $this->saveBase64Image($omangResponse['data']['photo_base64'], 'omang_photos');

                if (!$omangPhotoPath) {
                    return [
                        'success' => false,
                        'message' => 'Failed to process Omang photo',
                    ];
                }
            }

            // 3. Store selfie as a verification document
            $selfieDocument = VerificationDocument::create([
                'customer_profile_id' => $customerProfile->id,
                'document_type' => 'selfie',
                'file_path' => $selfiePhotoPath,
                'mime_type' => 'image/jpeg',
                'file_size' => Storage::disk('secure')->size($selfiePhotoPath),
                'uploaded_at' => now(),
                'verification_status' => 'pending',
            ]);

            // 4. Preprocess both images for optimal comparison
            $this->enhancedPreprocessImage($selfiePhotoPath);
            $this->enhancedPreprocessImage($omangPhotoPath);

            // 5. Check if faces can be detected in both images
            $selfieHasFace = $this->checkFaceDetection($selfiePhotoPath);
            $omangHasFace = $this->checkFaceDetection($omangPhotoPath);

            if (!$selfieHasFace || !$omangHasFace) {
                Log::warning('Face detection failed', [
                    'selfie_has_face' => $selfieHasFace,
                    'omang_has_face' => $omangHasFace,
                ]);

                return [
                    'success' => false,
                    'message' => !$selfieHasFace
                        ? 'No face detected in selfie. Please try again with a clearer photo in good lighting.'
                        : 'No face detected in ID photo. Please contact support.',
                ];
            }

            // 6. Compare the faces with enhanced error handling
            $comparisonResult = $this->enhancedFaceComparison($omangPhotoPath, $selfiePhotoPath);

            // 7. Process the comparison result
            $similarityScore = $comparisonResult['similarity_score'];
            $matchDetails = $comparisonResult['details'];
            $passed = $similarityScore >= $this->threshold;

            Log::info('Face comparison completed', [
                'customer_id' => $customerProfile->id,
                'similarity_score' => $similarityScore,
                'passed' => $passed,
            ]);

            // 8. Create a verification session
            $verificationSession = VerificationSession::create([
                'customer_profile_id' => $customerProfile->id,
                'omang_photo_path' => $omangPhotoPath,
                'selfie_photo_path' => $selfiePhotoPath,
                'similarity_score' => $similarityScore,
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
                'device_details' => json_encode([
                    'platform' => request()->header('sec-ch-ua-platform'),
                    'mobile' => request()->header('sec-ch-ua-mobile'),
                    'browser' => $this->getBrowserInfo(request()->userAgent()),
                ]),
                'location_details' => json_encode([
                    'match_details' => $matchDetails,
                    'ip' => request()->ip(),
                ]),
                'status' => $passed ? 'approved' : 'rejected',
            ]);

            // 9. Update the verification document status
            $selfieDocument->update([
                'verification_status' => $passed ? 'verified' : 'rejected',
                'verification_details' => json_encode([
                    'similarity_score' => $similarityScore,
                    'threshold' => $this->threshold,
                ]),
            ]);

            // 10. Return the verification result
            return [
                'success' => true,
                'similarity_score' => $similarityScore,
                'threshold' => $this->threshold,
                'passed' => $passed,
                'verification_session_id' => $verificationSession->id,
                'message' => $passed
                    ? 'Facial verification successful.'
                    : ($similarityScore > 50
                        ? 'Verification failed. The similarity score is below the required threshold. Please try again with better lighting and a clear view of your face.'
                        : 'Verification failed. The selfie does not match the ID photo. Please ensure you are using your own Omang.'),
            ];

        } catch (Exception $e) {
            Log::error('Face comparison error', [
                'customer_profile_id' => $customerProfile->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return [
                'success' => false,
                'message' => 'Error processing facial comparison: ' . $e->getMessage(),
            ];
        }
    }

    /**
     * Extract face from an Omang card
     * 
     * @param string $omangCardPath Path to the Omang card image
     * @return string|null Path to the extracted face
     */
    private function extractFaceFromOmangCard($omangCardPath): ?string
    {
        try {
            $imageContent = Storage::disk('secure')->get($omangCardPath);

            if (!$imageContent) {
                Log::error("Could not read Omang card image: {$omangCardPath}");
                return null;
            }

            // First, detect faces in the Omang card using AWS Rekognition
            $result = $this->rekognition->detectFaces([
                'Image' => [
                    'Bytes' => $imageContent,
                ],
                'Attributes' => ['DEFAULT'],
            ]);

            // Check if any faces were detected
            if (empty($result['FaceDetails'])) {
                Log::warning("No faces detected in Omang card: {$omangCardPath}");
                return null;
            }

            // Get the face with highest confidence
            usort($result['FaceDetails'], function ($a, $b) {
                return $b['Confidence'] <=> $a['Confidence'];
            });

            $faceDetail = $result['FaceDetails'][0];
            $confidence = $faceDetail['Confidence'];
            $boundingBox = $faceDetail['BoundingBox'];

            // Only proceed if confidence is high enough
            if ($confidence < 80) {
                Log::warning("Low confidence face detection in Omang card: {$confidence}%");
                return null;
            }

            // Load the image with Intervention Image
            $img = $this->imageManager->read($imageContent);

            // Get image dimensions
            $imgWidth = $img->width();
            $imgHeight = $img->height();

            // Calculate face coordinates from bounding box (normalized values 0-1)
            $faceX = intval($boundingBox['Left'] * $imgWidth);
            $faceY = intval($boundingBox['Top'] * $imgHeight);
            $faceWidth = intval($boundingBox['Width'] * $imgWidth);
            $faceHeight = intval($boundingBox['Height'] * $imgHeight);

            // Add a small margin around the face (15%)
            $margin = 0.15;
            $marginX = intval($faceWidth * $margin);
            $marginY = intval($faceHeight * $margin);

            // Adjust coordinates with margin, ensuring they stay within image bounds
            $faceX = max(0, $faceX - $marginX);
            $faceY = max(0, $faceY - $marginY);
            $faceWidth = min($imgWidth - $faceX, $faceWidth + (2 * $marginX));
            $faceHeight = min($imgHeight - $faceY, $faceHeight + (2 * $marginY));

            // Log the detected face
            Log::info('Face detected in Omang card', [
                'confidence' => $confidence,
                'bounding_box' => $boundingBox,
                'face_coordinates' => [
                    'x' => $faceX,
                    'y' => $faceY,
                    'width' => $faceWidth,
                    'height' => $faceHeight,
                ],
            ]);

            // Crop the face from the image
            // Use a try/catch block specifically for the crop operation
            try {
                // Try different crop approaches depending on the library version
                $croppedImg = $img->crop($faceWidth, $faceHeight, $faceX, $faceY);
            } catch (Exception $e) {
                Log::error("Error cropping image: {$e->getMessage()}");

                // Alternative approach if needed
                $croppedImg = $img->crop($faceWidth, $faceHeight, $faceX, $faceY);
            }

            // Enhance the face image for better recognition
            $croppedImg = $croppedImg->brightness(0.1);
            $croppedImg = $croppedImg->contrast(0.15);

            // Save the extracted face
            $facePath = 'extracted_faces/' . pathinfo($omangCardPath, PATHINFO_FILENAME) . '_face.jpg';
            $encodedImage = $croppedImg->toJpeg(95);

            Storage::disk('secure')->put($facePath, $encodedImage);

            Log::info('Face extracted from Omang card', [
                'original_path' => $omangCardPath,
                'extracted_path' => $facePath,
            ]);

            return $facePath;
        } catch (Exception $e) {
            Log::error("Error extracting face from Omang card: {$e->getMessage()}", [
                'trace' => $e->getTraceAsString(),
            ]);
            return null;
        }
    }

    /**
     * Extract browser information from user agent
     * 
     * @param string $userAgent
     * @return array
     */
    private function getBrowserInfo($userAgent)
    {
        $browser = 'Unknown';
        $version = 'Unknown';

        // Simple browser detection
        if (preg_match('/MSIE/i', $userAgent) || preg_match('/Trident/i', $userAgent)) {
            $browser = 'Internet Explorer';
        } elseif (preg_match('/Firefox/i', $userAgent)) {
            $browser = 'Firefox';
        } elseif (preg_match('/Chrome/i', $userAgent)) {
            $browser = 'Chrome';
        } elseif (preg_match('/Safari/i', $userAgent)) {
            $browser = 'Safari';
        } elseif (preg_match('/Opera/i', $userAgent)) {
            $browser = 'Opera';
        } elseif (preg_match('/Edge/i', $userAgent)) {
            $browser = 'Edge';
        }

        return [
            'name' => $browser,
            'user_agent' => $userAgent,
        ];
    }

    /**
     * Generate user-friendly feedback based on image quality assessment
     * 
     * @param array $qualityData
     * @return string|null
     */
    private function getQualityFeedback($qualityData)
    {
        if (empty($qualityData)) {
            return null;
        }

        $feedback = [];

        if (isset($qualityData['brightness']) && $qualityData['brightness'] < 0.4) {
            $feedback[] = 'The image appears too dark. Try taking a photo in better lighting.';
        }

        if (isset($qualityData['contrast']) && $qualityData['contrast'] < 0.3) {
            $feedback[] = 'The image has low contrast. Try taking a photo with better lighting conditions.';
        }

        if (isset($qualityData['face_size']) && $qualityData['face_size'] < 0.15) {
            $feedback[] = 'Your face appears too small in the image. Please position your face closer to the camera.';
        }

        if (isset($qualityData['blur']) && $qualityData['blur'] > 0.5) {
            $feedback[] = 'The image appears blurry. Try to hold the camera steady and ensure proper focus.';
        }

        return !empty($feedback) ? implode(' ', $feedback) : null;
    }

    /**
     * Enhanced preprocessing of images for optimal face comparison
     * 
     * @param string $imagePath
     * @return bool
     */
    private function enhancedPreprocessImage($imagePath): bool
    {
        try {
            $imageContent = Storage::disk('secure')->get($imagePath);

            if (!$imageContent) {
                Log::error("Could not read image file: {$imagePath}");
                return false;
            }

            // Create image instance with v3 syntax
            $img = $this->imageManager->read($imageContent);

            $width = $img->width();
            $height = $img->height();
            $imageSize = strlen($imageContent);

            Log::info("Image properties", [
                'path' => $imagePath,
                'width' => $width,
                'height' => $height,
                'size' => $imageSize,
            ]);

            $needsProcessing = false;

            // Check if image is too small
            if ($width < $this->minImageDimension || $height < $this->minImageDimension) {
                Log::warning("Image dimensions too small", [
                    'width' => $width,
                    'height' => $height,
                    'min_required' => $this->minImageDimension,
                ]);

                // Resize to minimum dimensions - v3 syntax
                // In v3, resize only needs width, and we use plain true for aspectRatio
                $img = $img->resize($this->minImageDimension,  true);

                $needsProcessing = true;
            }

            // Resize if image is too large for optimal processing
            if ($width > $this->optimalImageDimension || $height > $this->optimalImageDimension || $imageSize > $this->maxImageSize) {
                Log::info("Optimizing image dimensions", [
                    'original_width' => $width,
                    'original_height' => $height,
                    'target_dimension' => $this->optimalImageDimension,
                ]);

                // Resize to optimal dimensions - v3 syntax
                $img = $img->resize($this->optimalImageDimension,  true);

                $needsProcessing = true;
            }

            // Enhanced image processing
            if ($needsProcessing) {
                // Enhance image for better face detection
                $img = $img->brightness(0.05);  // 5% brightness increase
                $img = $img->contrast(0.1);     // 10% contrast increase

                // Save as high-quality JPEG
                $encodedImage = $img->toJpeg(95);

                // Save the processed image back to storage
                Storage::disk('secure')->put($imagePath, $encodedImage);

                Log::info("Image preprocessed successfully", [
                    'path' => $imagePath,
                    'new_width' => $img->width(),
                    'new_height' => $img->height(),
                    'new_size' => strlen($encodedImage),
                ]);
            }

            return true;
        } catch (Exception $e) {
            Log::error("Error preprocessing image: {$e->getMessage()}", [
                'trace' => $e->getTraceAsString(),
            ]);
            return false;
        }
    }

    /**
     * Assess image quality for better comparison results
     * 
     * @param string $imagePath
     * @return array Quality metrics
     */
    private function assessImageQuality($imagePath)
    {
        try {
            $imageContent = Storage::disk('secure')->get($imagePath);

            if (!$imageContent) {
                return ['error' => 'Could not read image file'];
            }

            // Check face detection first
            $faceDetails = $this->getFaceDetails($imagePath);

            // If no face detected, we can't assess face-specific quality
            if (empty($faceDetails)) {
                return [
                    'has_face' => false,
                    'overall_score' => 0.0,
                ];
            }

            // Basic image metrics
            $img = $this->imageManager->read($imageContent);
            $width = $img->width();
            $height = $img->height();
            $aspectRatio = $width / max(1, $height);

            // Calculate face size relative to image
            $faceWidth = $faceDetails['BoundingBox']['Width'] * $width;
            $faceHeight = $faceDetails['BoundingBox']['Height'] * $height;
            $faceSize = ($faceWidth * $faceHeight) / ($width * $height);

            // Get brightness and contrast metrics
            $brightness = $this->calculateBrightness($img);
            $contrast = $this->calculateContrast($img);

            // Confidence scores from AWS
            $quality = [
                'has_face' => true,
                'confidence' => $faceDetails['Confidence'] / 100,
                'face_size' => $faceSize,
                'brightness' => $brightness,
                'contrast' => $contrast,
                'pose' => [
                    'roll' => $faceDetails['Pose']['Roll'] ?? 0,
                    'yaw' => $faceDetails['Pose']['Yaw'] ?? 0,
                    'pitch' => $faceDetails['Pose']['Pitch'] ?? 0,
                ],
                'blur' => isset($faceDetails['Quality']['Brightness']) ? (1 - $faceDetails['Quality']['Sharpness'] / 100) : 0.5,
            ];

            // Calculate overall quality score (0-1)
            $quality['overall_score'] = $this->calculateOverallQuality($quality);

            return $quality;

        } catch (Exception $e) {
            Log::error("Error assessing image quality: {$e->getMessage()}");
            return ['error' => $e->getMessage()];
        }
    }

    /**
     * Calculate overall quality score from various metrics
     * 
     * @param array $metrics
     * @return float Score between 0-1
     */
    private function calculateOverallQuality($metrics)
    {
        // Weights for different factors
        $weights = [
            'confidence' => 0.3,
            'face_size' => 0.25,
            'brightness' => 0.15,
            'contrast' => 0.15,
            'blur' => 0.15
        ];

        // Calculate pose penalty (0-0.3)
        $posePenalty = 0;
        if (isset($metrics['pose'])) {
            $yawPenalty = abs($metrics['pose']['yaw']) > 15 ?
                min(1, abs($metrics['pose']['yaw']) / 45) * 0.1 : 0;

            $rollPenalty = abs($metrics['pose']['roll']) > 15 ?
                min(1, abs($metrics['pose']['roll']) / 45) * 0.1 : 0;

            $pitchPenalty = abs($metrics['pose']['pitch']) > 15 ?
                min(1, abs($metrics['pose']['pitch']) / 45) * 0.1 : 0;

            $posePenalty = $yawPenalty + $rollPenalty + $pitchPenalty;
        }

        // Calculate weighted score
        $score = 0;
        $score += ($metrics['confidence'] ?? 0) * $weights['confidence'];
        $score += ($metrics['face_size'] ?? 0) * $weights['face_size'];
        $score += ($metrics['brightness'] ?? 0) * $weights['brightness'];
        $score += ($metrics['contrast'] ?? 0) * $weights['contrast'];
        $score += (1 - ($metrics['blur'] ?? 0)) * $weights['blur'];

        // Apply pose penalty
        $score = max(0, min(1, $score - $posePenalty));

        return $score;
    }

    /**
     * Calculate image brightness (0-1)
     * 
     * @param mixed $image Intervention Image instance
     * @return float
     */
    private function calculateBrightness($image)
    {
        try {
            // Sample pixels for efficiency
            $width = $image->width();
            $height = $image->height();
            $sampleSize = min(100, $width, $height);
            $stepX = max(1, floor($width / $sampleSize));
            $stepY = max(1, floor($height / $sampleSize));

            $totalBrightness = 0;
            $sampleCount = 0;

            for ($x = 0; $x < $width; $x += $stepX) {
                for ($y = 0; $y < $height; $y += $stepY) {
                    // Get pixel color
                    $pixel = $image->pickColor($x, $y);

                    // Handle different return types from pickColor
                    if (is_object($pixel) && method_exists($pixel, 'toArray')) {
                        // If it's a Color object with toArray method
                        $rgb = $pixel->toArray();
                        $r = $rgb[0] ?? 0;
                        $g = $rgb[1] ?? 0;
                        $b = $rgb[2] ?? 0;
                    } elseif (is_object($pixel) && method_exists($pixel, 'red')) {
                        // If it's a Color object with individual color methods
                        $r = $pixel->red();
                        $g = $pixel->green();
                        $b = $pixel->blue();
                    } elseif (is_array($pixel)) {
                        // If it's already an array
                        $r = $pixel[0] ?? 0;
                        $g = $pixel[1] ?? 0;
                        $b = $pixel[2] ?? 0;
                    } elseif (is_int($pixel)) {
                        // If it's an integer (sometimes happens in older versions)
                        $r = ($pixel >> 16) & 0xFF;
                        $g = ($pixel >> 8) & 0xFF;
                        $b = $pixel & 0xFF;
                    } else {
                        // Fallback
                        $r = $g = $b = 0;
                    }

                    // Convert RGB to brightness value (0-1)
                    $brightness = ($r + $g + $b) / (3 * 255);
                    $totalBrightness += $brightness;
                    $sampleCount++;
                }
            }

            return $sampleCount > 0 ? $totalBrightness / $sampleCount : 0.5;
        } catch (Exception $e) {
            Log::error("Error calculating brightness: {$e->getMessage()}", [
                'exception' => get_class($e),
                'message' => $e->getMessage(),
            ]);
            return 0.5; // Default mid-value
        }
    }

    /**
     * Calculate image contrast (0-1)
     * 
     * @param mixed $image Intervention Image instance
     * @return float
     */
    private function calculateContrast($image)
    {
        try {
            // Sample pixels for efficiency
            $width = $image->width();
            $height = $image->height();
            $sampleSize = min(100, $width, $height);
            $stepX = max(1, floor($width / $sampleSize));
            $stepY = max(1, floor($height / $sampleSize));

            $pixels = [];

            for ($x = 0; $x < $width; $x += $stepX) {
                for ($y = 0; $y < $height; $y += $stepY) {
                    // Get pixel color
                    $pixel = $image->pickColor($x, $y);

                    // Handle different return types from pickColor
                    if (is_object($pixel) && method_exists($pixel, 'toArray')) {
                        // If it's a Color object with toArray method
                        $rgb = $pixel->toArray();
                        $r = $rgb[0] ?? 0;
                        $g = $rgb[1] ?? 0;
                        $b = $rgb[2] ?? 0;
                    } elseif (is_object($pixel) && method_exists($pixel, 'red')) {
                        // If it's a Color object with individual color methods
                        $r = $pixel->red();
                        $g = $pixel->green();
                        $b = $pixel->blue();
                    } elseif (is_array($pixel)) {
                        // If it's already an array
                        $r = $pixel[0] ?? 0;
                        $g = $pixel[1] ?? 0;
                        $b = $pixel[2] ?? 0;
                    } elseif (is_int($pixel)) {
                        // If it's an integer (sometimes happens in older versions)
                        $r = ($pixel >> 16) & 0xFF;
                        $g = ($pixel >> 8) & 0xFF;
                        $b = $pixel & 0xFF;
                    } else {
                        // Fallback
                        $r = $g = $b = 0;
                    }

                    // Calculate luminance
                    $luminance = 0.299 * $r + 0.587 * $g + 0.114 * $b;
                    $pixels[] = $luminance;
                }
            }

            if (count($pixels) < 2) {
                return 0.5;
            }

            // Calculate standard deviation of luminance
            $mean = array_sum($pixels) / count($pixels);
            $variance = 0;

            foreach ($pixels as $pixel) {
                $variance += pow($pixel - $mean, 2);
            }

            $stdDev = sqrt($variance / count($pixels));

            // Normalize to 0-1 range (typical std dev range for images is 0-128)
            return min(1, $stdDev / 64);

        } catch (Exception $e) {
            Log::error("Error calculating contrast: {$e->getMessage()}", [
                'exception' => get_class($e),
                'message' => $e->getMessage(),
            ]);
            return 0.5; // Default mid-value
        }
    }

    /**
     * Get detailed face information from an image
     * 
     * @param string $imagePath
     * @return array|null Face details or null if no face detected
     */
    private function getFaceDetails($imagePath)
    {
        try {
            $imageContent = Storage::disk('secure')->get($imagePath);

            if (!$imageContent) {
                Log::error("Could not read image file for face details: {$imagePath}");
                return null;
            }

            $result = $this->rekognition->detectFaces([
                'Image' => [
                    'Bytes' => $imageContent,
                ],
                'Attributes' => ['ALL'], // Get all attributes for detailed analysis
            ]);

            if (empty($result['FaceDetails'])) {
                return null;
            }

            // Return the face with highest confidence
            usort($result['FaceDetails'], function ($a, $b) {
                return $b['Confidence'] <=> $a['Confidence'];
            });

            return $result['FaceDetails'][0];
        } catch (Exception $e) {
            Log::error("Error getting face details: {$e->getMessage()}");
            return null;
        }
    }

    /**
     * Check if a face can be detected in the image with enhanced logging
     * 
     * @param string $imagePath
     * @return bool
     */
    private function checkFaceDetection($imagePath)
    {
        try {
            $imageContent = Storage::disk('secure')->get($imagePath);

            if (!$imageContent) {
                Log::error("Could not read image file: {$imagePath}");
                return false;
            }

            // Use a more reliable face detection configuration
            $result = $this->rekognition->detectFaces([
                'Image' => [
                    'Bytes' => $imageContent,
                ],
                'Attributes' => ['DEFAULT'],
            ]);

            $faceCount = count($result['FaceDetails'] ?? []);
            $confidence = $faceCount > 0 ? $result['FaceDetails'][0]['Confidence'] : 0;

            Log::info("Face detection completed", [
                'image_path' => $imagePath,
                'face_count' => $faceCount,
                'confidence' => $confidence,
            ]);

            return $faceCount > 0 && $confidence >= 80; // Only accept high confidence detections
        } catch (AwsException $e) {
            Log::error('AWS Rekognition DetectFaces error', [
                'error' => $e->getMessage(),
                'aws_error_type' => $e->getAwsErrorType(),
                'aws_error_code' => $e->getAwsErrorCode(),
            ]);
            return false;
        } catch (Exception $e) {
            Log::error("Error detecting faces: {$e->getMessage()}");
            return false;
        }
    }

    /**
     * Enhanced face comparison with optimized parameters and retry logic
     * 
     * @param string $sourceImagePath Path to the ID photo
     * @param string $targetImagePath Path to the selfie
     * @return array Comparison result
     */
    private function enhancedFaceComparison(string $sourceImagePath, string $targetImagePath)
    {
        $attempts = 0;
        $maxAttempts = $this->maxRetries + 1;
        $lastError = null;

        while ($attempts < $maxAttempts) {
            try {
                $attempts++;

                // Get the image content
                $sourceImage = Storage::disk('secure')->get($sourceImagePath);
                $targetImage = Storage::disk('secure')->get($targetImagePath);

                if (!$sourceImage || !$targetImage) {
                    Log::error('Could not read image files', [
                        'source_exists' => (bool) $sourceImage,
                        'target_exists' => (bool) $targetImage,
                    ]);

                    return [
                        'similarity_score' => 0,
                        'details' => [
                            'error' => 'Could not read image files',
                            'source' => 'file_error',
                            'attempt' => $attempts,
                        ],
                    ];
                }

                // Call Rekognition with optimized parameters
                $result = $this->rekognition->compareFaces([
                    'SourceImage' => [
                        'Bytes' => $sourceImage,
                    ],
                    'TargetImage' => [
                        'Bytes' => $targetImage,
                    ],
                    'SimilarityThreshold' => 0, // Get all matches regardless of score
                    'QualityFilter' => 'AUTO', // Use auto quality filtering
                ]);

                // Process the result
                if (isset($result['FaceMatches']) && count($result['FaceMatches']) > 0) {
                    // Sort matches by similarity (highest first)
                    $matches = collect($result['FaceMatches'])
                        ->sortByDesc(function ($match) {
                            return $match['Similarity'];
                        })
                        ->values()
                        ->all();

                    // Get the highest similarity score
                    $highestMatch = $matches[0];
                    $similarityScore = $highestMatch['Similarity'];

                    // Add additional metrics for analysis
                    $sourceFaceDetails = isset($result['SourceImageFace']) ? $result['SourceImageFace'] : null;
                    $targetFaceDetails = isset($highestMatch['Face']) ? $highestMatch['Face'] : null;

                    $matchDetails = [
                        'face_count' => count($result['FaceMatches']),
                        'unmatched_faces' => count($result['UnmatchedFaces'] ?? []),
                        'highest_match' => [
                            'similarity' => $similarityScore,
                            'confidence' => $highestMatch['Face']['Confidence'],
                            'bounding_box' => $highestMatch['Face']['BoundingBox'],
                        ],
                        'source_face' => $sourceFaceDetails,
                        'target_face' => $targetFaceDetails,
                        'source' => 'aws_rekognition',
                        'timestamp' => now()->toIso8601String(),
                        'attempt' => $attempts,
                    ];

                    return [
                        'similarity_score' => $similarityScore,
                        'details' => $matchDetails,
                    ];
                } else {
                    // No matching faces found
                    Log::warning('No matching faces found', [
                        'unmatched_faces' => count($result['UnmatchedFaces'] ?? []),
                        'attempt' => $attempts,
                    ]);

                    // If no face matches and we haven't reached max attempts, retry
                    if ($attempts < $maxAttempts) {
                        Log::info("Retrying face comparison (attempt {$attempts} of {$maxAttempts})");
                        sleep(1); // Brief delay before retry
                        continue;
                    }

                    return [
                        'similarity_score' => 0,
                        'details' => [
                            'error' => 'No matching faces found after multiple attempts',
                            'unmatched_faces' => count($result['UnmatchedFaces'] ?? []),
                            'source' => 'aws_rekognition',
                            'attempts' => $attempts,
                        ],
                    ];
                }
            } catch (AwsException $e) {
                $lastError = $e;
                Log::error('AWS Rekognition error during comparison', [
                    'error' => $e->getMessage(),
                    'aws_error_code' => $e->getAwsErrorCode(),
                    'attempt' => $attempts,
                ]);

                // Retry if not last attempt
                if ($attempts < $maxAttempts) {
                    Log::info("Retrying after AWS error (attempt {$attempts} of {$maxAttempts})");
                    sleep(1); // Brief delay before retry
                    continue;
                }
            } catch (Exception $e) {
                $lastError = $e;
                Log::error('General error during face comparison', [
                    'error' => $e->getMessage(),
                    'attempt' => $attempts,
                ]);

                // Retry if not last attempt
                if ($attempts < $maxAttempts) {
                    Log::info("Retrying after general error (attempt {$attempts} of {$maxAttempts})");
                    sleep(1); // Brief delay before retry
                    continue;
                }
            }
        }

        // If we got here, all attempts failed
        return [
            'similarity_score' => 0,
            'details' => [
                'error' => $lastError ? 'Error during face comparison: ' . $lastError->getMessage() : 'Unknown error',
                'source' => 'error',
                'attempts' => $attempts,
            ],
        ];
    }

    /**
     * Save an image file to storage with enhanced validation
     * 
     * @param string|UploadedFile $image
     * @param string $directory
     * @return string|null
     */
    private function saveImage($image, string $directory)
    {
        try {
            // Handle uploaded file
            if ($image instanceof UploadedFile) {
                // Validate the file
                if (!$image->isValid() || !Str::startsWith($image->getMimeType(), 'image/')) {
                    Log::error('Invalid image upload', [
                        'mime_type' => $image->getMimeType(),
                        'error' => $image->getError(),
                    ]);
                    return null;
                }

                $path = $image->store($directory, 'secure');
                return $path;
            }

            // Handle base64 or data URL
            if (is_string($image) && Str::startsWith($image, 'data:image')) {
                return $this->saveBase64Image($image, $directory);
            }

            // Handle file path string
            if (is_string($image) && file_exists($image)) {
                $fileContent = file_get_contents($image);
                $fileName = $directory . '/' . Str::uuid()->toString() . '.jpg';
                Storage::disk('secure')->put($fileName, $fileContent);
                return $fileName;
            }

            Log::error('Invalid image format provided', [
                'type' => gettype($image),
                'is_string' => is_string($image),
                'string_start' => is_string($image) ? substr($image, 0, 30) . '...' : 'N/A',
            ]);

            return null;

        } catch (Exception $e) {
            Log::error('Error saving image', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            return null;
        }
    }

    /**
     * Save a base64 encoded image with enhanced error handling
     * 
     * @param string $base64Image
     * @param string $directory
     * @return string|null
     */
    protected function saveBase64Image($base64Image, $directory)
    {
        try {
            // Log the format of the base64 image (first 30 characters) for debugging
            $prefix = substr($base64Image, 0, 30);
            Log::info('Base64 image format prefix: ' . $prefix);

            // Check if the base64 string contains a data URI scheme
            if (strpos($base64Image, 'data:image') === 0) {
                // Extract the base64 encoded image data without the prefix
                list($type, $data) = explode(';', $base64Image);
                list(, $data) = explode(',', $data);
                $base64Image = $data;
            }

            // Validate the base64 string
            if (!preg_match('%^[a-zA-Z0-9/+]*={0,2}$%', $base64Image)) {
                Log::error('Invalid base64 string format');
                return null;
            }

            // Decode the base64 string
            $imageData = base64_decode($base64Image, true);

            if (!$imageData) {
                Log::error('Error decoding base64 image');
                return null;
            }

            // Additional validation - check if it's a valid image
            if (!$this->isValidImage($imageData)) {
                Log::error('Invalid image data after base64 decode');
                return null;
            }

            // Generate a unique filename using UUID
            $filename = Str::uuid()->toString() . '.jpg';
            $path = $directory . '/' . $filename;

            // Save the raw image data directly to storage
            if (Storage::disk('secure')->put($path, $imageData)) {
                Log::info('Image saved successfully to: ' . $path);
                return $path;
            } else {
                Log::error('Error saving image to storage');
                return null;
            }
        } catch (Exception $e) {
            Log::error('Error saving base64 image', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return null;
        }
    }

    /**
     * Check if data is a valid image
     * 
     * @param string $data
     * @return bool
     */
    private function isValidImage($data)
    {
        try {
            // Check for common image headers (JPEG, PNG, etc.)
            $signatures = [
                // JPEG
                "\xFF\xD8\xFF",
                // PNG
                "\x89\x50\x4E\x47\x0D\x0A\x1A\x0A",
                // GIF
                "GIF87a",
                "GIF89a",
                // BMP
                "BM",
            ];

            foreach ($signatures as $signature) {
                if (substr($data, 0, strlen($signature)) === $signature) {
                    return true;
                }
            }

            // As a fallback, try to create an image from the data
            $img = @imagecreatefromstring($data);
            return $img !== false;

        } catch (Exception $e) {
            Log::error('Error validating image data: ' . $e->getMessage());
            return false;
        }
    }
}