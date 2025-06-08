<?php

namespace App\Services\Mock;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;

/**
 * Mock Omang API Service for Development and Testing
 * 
 * This file provides routes that simulate the Botswana National Identity (Omang) API.
 * To use it, include this file in your routes/web.php for development environments only.
 * 
 * Example:
 * if (app()->environment('local', 'testing')) {
 *     require_once app_path('Services/Mock/MockOmangApi.php');
 * }
 */

// Define mock data for testing
$mockData = [
    // Valid Omang records
    '123456789' => [
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
        'photo_path' => 'mock/omang_photos/female1.jpg'
    ],
    '987654321' => [
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
        'photo_path' => 'mock/omang_photos/male1.jpg'
    ],
    '111222333' => [
        'omang_number' => '111222333',
        'first_name' => 'Lesego',
        'middle_name' => 'Ruth',
        'last_name' => 'Tau',
        'date_of_birth' => '1988-04-22',
        'gender' => 'female',
        'nationality' => 'Botswana',
        'issue_date' => '2019-01-10',
        'expiry_date' => '2029-01-09',
        'place_of_birth' => 'Maun',
        'has_photo' => true,
        'photo_path' => 'mock/omang_photos/female2.jpg'
    ],
    '444555666' => [
        'omang_number' => '444555666',
        'first_name' => 'Kagiso',
        'middle_name' => '',
        'last_name' => 'Pule',
        'date_of_birth' => '1975-12-03',
        'gender' => 'male',
        'nationality' => 'Botswana',
        'issue_date' => '2017-05-20',
        'expiry_date' => '2027-05-19',
        'place_of_birth' => 'Lobatse',
        'has_photo' => true,
        'photo_path' => 'mock/omang_photos/male2.jpg'
    ],
    
    // Invalid cases
    '555555555' => null, // Not found
    '000000000' => null, // Not found
];

// Function to validate API key
function validateApiKey(Request $request) {
    $apiKey = $request->header('X-API-Key');
    $validKey = config('services.omang.key', 'test_api_key');
    
    if (!$apiKey || $apiKey !== $validKey) {
        return response()->json([
            'success' => false,
            'message' => 'Invalid or missing API key',
        ], 401);
    }
    
    return true;
}

// Function to simulate network latency
function simulateLatency() {
    // Random delay between 100ms and 1000ms
    usleep(rand(100000, 1000000));
}

// Function to simulate occasional errors
function simulateErrors() {
    // 5% chance of a random error
    if (rand(1, 100) <= 5) {
        $errorMessages = [
            'Service temporarily unavailable',
            'Request timed out',
            'Internal server error',
            'Too many requests',
            'Bad gateway',
        ];
        
        return response()->json([
            'success' => false,
            'message' => $errorMessages[array_rand($errorMessages)],
        ], rand(500, 503));
    }
    
    return false;
}

// Routes for the mock API
Route::prefix('mock/omang-api')->group(function () use ($mockData) {
    // Verify Omang number
    Route::post('/api/verify', function (Request $request) use ($mockData) {
        // Validate API key
        $keyValidation = validateApiKey($request);
        if ($keyValidation !== true) {
            return $keyValidation;
        }
        
        // Simulate network latency
        simulateLatency();
        
        // Simulate random errors
        $error = simulateErrors();
        if ($error) {
            return $error;
        }
        
        // Validate request
        $request->validate([
            'omang_number' => 'required|string|size:9',
        ]);
        
        $omangNumber = $request->input('omang_number');
        
        // Check if Omang exists
        if (!isset($mockData[$omangNumber]) || $mockData[$omangNumber] === null) {
            return response()->json([
                'success' => false,
                'message' => 'Omang number not found in system',
            ]);
        }
        
        // Return success response
        return response()->json([
            'success' => true,
            'data' => $mockData[$omangNumber],
        ]);
    });
    
    // Get Omang photo
    Route::get('/api/photos/{omangNumber}', function (Request $request, $omangNumber) use ($mockData) {
        // Validate API key
        $keyValidation = validateApiKey($request);
        if ($keyValidation !== true) {
            return $keyValidation;
        }
        
        // Simulate network latency
        simulateLatency();
        
        // Simulate random errors
        $error = simulateErrors();
        if ($error) {
            return $error;
        }
        
        // Check if Omang exists
        if (!isset($mockData[$omangNumber]) || $mockData[$omangNumber] === null) {
            return response()->json([
                'success' => false,
                'message' => 'Omang number not found in system',
            ]);
        }
        
        // Check if photo exists
        if (!$mockData[$omangNumber]['has_photo']) {
            return response()->json([
                'success' => false,
                'message' => 'No photo available for this Omang number',
            ]);
        }
        
        // Get photo path
        $photoPath = $mockData[$omangNumber]['photo_path'];
        
        // Check if the photo file exists in storage
        if (!Storage::exists($photoPath)) {
            // Create directory if it doesn't exist
            $directory = dirname($photoPath);
            if (!Storage::exists($directory)) {
                Storage::makeDirectory($directory);
            }
            
            // Generate a placeholder image based on gender
            $gender = $mockData[$omangNumber]['gender'];
            $placeholderPath = public_path("images/placeholders/{$gender}.jpg");
            
            if (file_exists($placeholderPath)) {
                Storage::put($photoPath, file_get_contents($placeholderPath));
            } else {
                // If placeholder doesn't exist, create a simple colored image
                $image = imagecreatetruecolor(300, 400);
                $bgColor = ($gender === 'female') ? 
                    imagecolorallocate($image, 255, 200, 200) : 
                    imagecolorallocate($image, 200, 200, 255);
                imagefill($image, 0, 0, $bgColor);
                
                // Add text
                $textColor = imagecolorallocate($image, 50, 50, 50);
                $text = $mockData[$omangNumber]['first_name'] . ' ' . $mockData[$omangNumber]['last_name'];
                imagestring($image, 5, 100, 180, $text, $textColor);
                imagestring($image, 3, 80, 210, "Omang: {$omangNumber}", $textColor);
                
                // Save the image
                ob_start();
                imagejpeg($image);
                $imageData = ob_get_clean();
                imagedestroy($image);
                
                Storage::put($photoPath, $imageData);
            }
        }
        
        // Get the photo as base64
        $photoData = Storage::get($photoPath);
        $base64Photo = 'data:image/jpeg;base64,' . base64_encode($photoData);
        
        // Return success response
        return response()->json([
            'success' => true,
            'data' => [
                'omang_number' => $omangNumber,
                'photo_base64' => $base64Photo,
            ],
        ]);
    });
    
    // Health check endpoint
    Route::get('/health', function () {
        return response()->json([
            'status' => 'healthy',
            'timestamp' => now()->toIso8601String(),
            'environment' => app()->environment(),
        ]);
    });
});

// Add a setup endpoint to ensure mock data is properly initialized
Route::get('/mock/omang-api/setup', function () {
    // Create mock directory
    if (!Storage::exists('mock/omang_photos')) {
        Storage::makeDirectory('mock/omang_photos');
    }
    
    // Create directory for placeholder images
    if (!is_dir(public_path('images/placeholders'))) {
        mkdir(public_path('images/placeholders'), 0755, true);
    }
    
    // Create sample placeholder images
    foreach (['male', 'female'] as $gender) {
        $placeholderPath = public_path("images/placeholders/{$gender}.jpg");
        
        if (!file_exists($placeholderPath)) {
            $image = imagecreatetruecolor(300, 400);
            $bgColor = ($gender === 'female') ? 
                imagecolorallocate($image, 255, 200, 200) : 
                imagecolorallocate($image, 200, 200, 255);
            imagefill($image, 0, 0, $bgColor);
            
            // Add text
            $textColor = imagecolorallocate($image, 50, 50, 50);
            imagestring($image, 5, 100, 180, "Sample {$gender}", $textColor);
            imagestring($image, 3, 90, 210, "Placeholder Image", $textColor);
            
            // Save the image
            imagejpeg($image, $placeholderPath);
            imagedestroy($image);
        }
    }
    
    return response()->json([
        'success' => true,
        'message' => 'Mock Omang API setup complete',
        'mock_api_url' => url('/mock/omang-api'),
        'endpoints' => [
            'verify' => 'POST /mock/omang-api/api/verify',
            'photos' => 'GET /mock/omang-api/api/photos/{omangNumber}',
            'health' => 'GET /mock/omang-api/health',
        ],
        'test_omang_numbers' => [
            'valid' => ['123456789', '987654321', '111222333', '444555666'],
            'invalid' => ['555555555', '000000000'],
        ],
    ]);
});