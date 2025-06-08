<?php

namespace App\Http\Controllers;

use App\Http\Requests\FacialVerificationRequest;
use App\Models\CustomerProfile;
use App\Models\VerificationDocument;
use App\Models\VerificationSession;
use App\Services\FacialComparisonService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver as GdDriver;

class FacialVerificationController extends Controller
{
    protected $facialComparisonService;
    protected $imageManager;

    public function __construct(FacialComparisonService $facialComparisonService)
    {
        $this->facialComparisonService = $facialComparisonService;

        // Initialize Image Manager with GD driver (or you can use Imagick if available)
        $this->imageManager = new ImageManager(new GdDriver());
    }

    /**
     * Show facial verification page
     */
    public function showVerificationPage()
    {
        $customerProfile = Auth::user()->customerProfile;

        if (!$customerProfile || !$customerProfile->isVerified()) {
            return redirect()->route('verification.omang');
        }

        // Get the required documents
        $requiredDocumentTypes = ['omang_front', 'omang_back', 'proof_of_address'];
        $documents = $customerProfile->documents()->whereIn('document_type', $requiredDocumentTypes)->get();
        $documentTypes = $documents->pluck('document_type')->toArray();

        // Check if all required documents are uploaded
        foreach ($requiredDocumentTypes as $type) {
            if (!in_array($type, $documentTypes)) {
                return redirect()->route('verification.documents')
                    ->with('error', 'Please upload all required documents first.');
            }
        }

        // Get the latest verification session if exists
        $latestSession = $customerProfile->verificationSessions()->latest()->first();

        return view('verification.facial', compact('customerProfile', 'latestSession'));
    }

    /**
     * Process facial verification
     */
    public function processVerification(FacialVerificationRequest $request)
    {
        try {
            $customerProfile = Auth::user()->customerProfile;

            if (!$customerProfile || !$customerProfile->isVerified()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Please complete Omang verification first',
                ]);
            }

            // Process the selfie image
            if ($request->hasFile('selfie')) {
                $selfieImage = $request->file('selfie');
            } elseif ($request->filled('selfie_base64')) {
                $selfieImage = $this->processBase64Image($request->input('selfie_base64'));
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'No selfie image provided',
                ]);
            }

            // Compare faces
            $result = $this->facialComparisonService->compareFaces($customerProfile, $selfieImage);

            if (!$result['success']) {
                return response()->json([
                    'success' => false,
                    'message' => $result['message'] ?? 'Facial verification failed',
                ]);
            }

            // Log the verification attempt
            $this->logVerificationAttempt($customerProfile, $result);

            return response()->json([
                'success' => true,
                'similarity_score' => $result['similarity_score'],
                'threshold' => $result['threshold'],
                'passed' => $result['passed'],
                'verification_session_id' => $result['verification_session_id'],
            ]);
        } catch (\Exception $e) {
            Log::error('Facial verification error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'An error occurred during facial verification: ' . $e->getMessage(),
            ]);
        }
    }

    /**
     * Get verification session result
     */
    public function getVerificationResult(Request $request, $sessionId)
    {
        $customerProfile = Auth::user()->customerProfile;
        $session = $customerProfile->verificationSessions()->findOrFail($sessionId);

        return response()->json([
            'success' => true,
            'session' => $session,
            'passed' => $session->status === 'approved',
            'similarity_score' => $session->similarity_score,
            'threshold' => config('verification.facial_threshold', 70),
        ]);
    }

    /**
     * Process a base64 image
     */
    protected function processBase64Image($base64Data)
    {
        // Remove data URL part if present
        if (strpos($base64Data, ';base64,') !== false) {
            list($type, $data) = explode(';', $base64Data);
            list(, $data) = explode(',', $data);
            $base64Data = $data;
        }

        // Decode the base64 data
        $imageData = base64_decode($base64Data);

        // Create a temporary file
        $tempFile = tempnam(sys_get_temp_dir(), 'selfie_');
        file_put_contents($tempFile, $imageData);

        try {
            // Optimize the image - using Intervention Image v3 syntax
            $image = $this->imageManager->read($tempFile);

            // In v3, use scale() method instead of resize()
            // This maintains the aspect ratio while setting the width to 800px
            $image = $image->scale(width: 800);

            // Generate the encoded image with 90% quality
            $encodedImage = $image->toJpeg(90);

            // Write the processed image back to the temp file
            file_put_contents($tempFile, $encodedImage);

            return $tempFile;
        } catch (\Exception $e) {
            // Log error
            \Log::error('Error processing base64 image: ' . $e->getMessage());

            // Return original temp file even if processing failed
            return $tempFile;
        }
    }

    /**
     * Log the verification attempt
     */
    protected function logVerificationAttempt($customerProfile, $result)
    {
        activity()
            ->performedOn($customerProfile)
            ->causedBy(Auth::user())
            ->withProperties([
                'similarity_score' => $result['similarity_score'],
                'threshold' => $result['threshold'],
                'passed' => $result['passed'],
                'verification_session_id' => $result['verification_session_id'],
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
            ])
            ->log('facial_verification_attempt');
    }
}