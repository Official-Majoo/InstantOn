<?php

namespace App\Services;

use App\Models\CustomerProfile;
use App\Models\OmangVerificationLog;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Facades\Log;

class OmangApiService
{
    protected $client;
    protected $baseUrl;
    protected $apiKey;

    public function __construct()
    {
        $this->baseUrl = config('services.omang.url');
        $this->apiKey = config('services.omang.key');
        
        $this->client = new Client([
            'base_uri' => $this->baseUrl,
            'timeout'  => 30,
            'headers' => [
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
                'X-API-Key' => $this->apiKey,
            ],
        ]);
    }

    /**
     * Verify an Omang number against the API
     * 
     * @param string $omangNumber
     * @param CustomerProfile|null $customerProfile
     * @return array
     */
    public function verifyOmang(string $omangNumber, ?CustomerProfile $customerProfile = null)
    {
        $requestPayload = [
            'omang_number' => $omangNumber,
            'request_id' => uniqid('fnbb_', true),
            'timestamp' => now()->toIso8601String(),
        ];

        try {
            // For development, we'll use a mock response
            if (app()->environment('local', 'testing')) {
                $response = $this->getMockResponse($omangNumber);
            } else {
                $apiResponse = $this->client->post('/api/verify', [
                    'json' => $requestPayload
                ]);
                
                $response = json_decode($apiResponse->getBody()->getContents(), true);
            }

            $isSuccessful = isset($response['success']) && $response['success'] === true;

            // Log the verification attempt
            if ($customerProfile) {
                OmangVerificationLog::create([
                    'customer_profile_id' => $customerProfile->id,
                    'omang_number' => $omangNumber,
                    'request_payload' => json_encode($requestPayload),
                    'response_payload' => json_encode($response),
                    'is_successful' => $isSuccessful,
                    'error_message' => $isSuccessful ? null : ($response['message'] ?? 'Unknown error'),
                    'verification_timestamp' => now(),
                ]);
            }

            return $response;
        } catch (GuzzleException $e) {
            Log::error('Omang API Error', [
                'omang_number' => $omangNumber,
                'error' => $e->getMessage()
            ]);

            $errorResponse = [
                'success' => false,
                'message' => 'API connection error: ' . $e->getMessage(),
            ];

            // Log the failed verification attempt
            if ($customerProfile) {
                OmangVerificationLog::create([
                    'customer_profile_id' => $customerProfile->id,
                    'omang_number' => $omangNumber,
                    'request_payload' => json_encode($requestPayload),
                    'response_payload' => json_encode($errorResponse),
                    'is_successful' => false,
                    'error_message' => $e->getMessage(),
                    'verification_timestamp' => now(),
                ]);
            }

            return $errorResponse;
        }
    }

    /**
     * Get Omang photo from the API
     * 
     * @param string $omangNumber
     * @return array
     */
    public function getOmangPhoto(string $omangNumber)
    {
        try {
            // For development, we'll use a mock response
            if (app()->environment('local', 'testing')) {
                return $this->getMockPhotoResponse($omangNumber);
            }

            $apiResponse = $this->client->get("/api/photos/{$omangNumber}", [
                'headers' => [
                    'X-Request-ID' => uniqid('fnbb_photo_', true),
                ]
            ]);
            
            $response = json_decode($apiResponse->getBody()->getContents(), true);
            
            return $response;
        } catch (GuzzleException $e) {
            Log::error('Omang Photo API Error', [
                'omang_number' => $omangNumber,
                'error' => $e->getMessage()
            ]);

            return [
                'success' => false,
                'message' => 'API connection error: ' . $e->getMessage(),
            ];
        }
    }

    /**
     * Generate a mock response for development/testing
     * 
     * @param string $omangNumber
     * @return array
     */
    private function getMockResponse(string $omangNumber)
    {
        // Validate Omang format (should be like: 123456789)
        if (!preg_match('/^\d{9}$/', $omangNumber)) {
            return [
                'success' => false,
                'message' => 'Invalid Omang number format',
            ];
        }

        // Mock specific Omang numbers for testing
        $testCases = [
            '123456789' => [
                'success' => true,
                'data' => [
                    'omang_number' => '123456789',
                    'first_name' => 'Mpho',
                    'middle_name' => '',
                    'last_name' => 'Kgosi',
                    'date_of_birth' => '1985-06-15',
                    'gender' => 'female',
                    'nationality' => 'Botswana',
                    'issue_date' => '2018-03-10',
                    'expiry_date' => '2028-03-09',
                    'place_of_birth' => 'Gaborone',
                    'has_photo' => true,
                ]
            ],
            '987654321' => [
                'success' => true,
                'data' => [
                    'omang_number' => '987654321',
                    'first_name' => 'Thabo',
                    'middle_name' => 'Michael',
                    'last_name' => 'Moeng',
                    'date_of_birth' => '1992-11-28',
                    'gender' => 'male',
                    'nationality' => 'Botswana',
                    'issue_date' => '2020-07-15',
                    'expiry_date' => '2030-07-14',
                    'place_of_birth' => 'Francistown',
                    'has_photo' => true,
                ]
            ],
            '555555555' => [
                'success' => false,
                'message' => 'Omang number not found in system',
            ],
        ];

        // Return test case if it exists, otherwise generate a random one
        if (isset($testCases[$omangNumber])) {
            return $testCases[$omangNumber];
        }

        // Generate a random response for other Omang numbers
        $isSuccess = rand(0, 10) < 9; // 90% success rate

        if (!$isSuccess) {
            return [
                'success' => false,
                'message' => 'Random API error for testing purposes',
            ];
        }

        $firstNames = ['Tumelo', 'Lesego', 'Kgosi', 'Botho', 'Lorato', 'Kagiso', 'Masego'];
        $lastNames = ['Mokgadi', 'Tau', 'Kgosi', 'Pule', 'Molefe', 'Moeng', 'Serame'];
        
        return [
            'success' => true,
            'data' => [
                'omang_number' => $omangNumber,
                'first_name' => $firstNames[array_rand($firstNames)],
                'middle_name' => rand(0, 1) ? $firstNames[array_rand($firstNames)] : '',
                'last_name' => $lastNames[array_rand($lastNames)],
                'date_of_birth' => date('Y-m-d', strtotime('-' . rand(18, 70) . ' years')),
                'gender' => rand(0, 1) ? 'male' : 'female',
                'nationality' => 'Botswana',
                'issue_date' => date('Y-m-d', strtotime('-' . rand(1, 5) . ' years')),
                'expiry_date' => date('Y-m-d', strtotime('+' . rand(3, 10) . ' years')),
                'place_of_birth' => ['Gaborone', 'Francistown', 'Maun', 'Serowe'][array_rand(['Gaborone', 'Francistown', 'Maun', 'Serowe'])],
                'has_photo' => true,
            ]
        ];
    }

    /**
     * Generate a mock photo response for development/testing
     * 
     * @param string $omangNumber
     * @return array
     */
    private function getMockPhotoResponse(string $omangNumber)
    {
        // In a real implementation, we would return a base64 encoded image
        // For testing, we'll return a placeholder
        
        if (in_array($omangNumber, ['555555555'])) {
            return [
                'success' => false,
                'message' => 'Photo not found for this Omang number',
            ];
        }

        return [
            'success' => true,
            'data' => [
                'omang_number' => $omangNumber,
                'photo_base64' => 'data:image/jpeg;base64,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',
            ]
        ];
    }
}