<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Facial Recognition Service
    |--------------------------------------------------------------------------
    |
    | This option controls which facial recognition service to use.
    | Options include AWS Rekognition, Azure Face API, or local processing.
    |
    | Supported: "aws", "azure", "local"
    |
    */

    'service' => env('FACIAL_RECOGNITION_SERVICE', 'local'),

    /*
    |--------------------------------------------------------------------------
    | AWS Rekognition Settings
    |--------------------------------------------------------------------------
    |
    | Configuration for AWS Rekognition when used as the facial
    | recognition service. These are used only when service is "aws".
    |
    */

    'aws' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
        'similarity_threshold' => env('AWS_FACE_SIMILARITY_THRESHOLD', 80),
    ],

    'facial_threshold' => env('FACIAL_VERIFICATION_THRESHOLD', 70),

    /*
    |--------------------------------------------------------------------------
    | Azure Face API Settings
    |--------------------------------------------------------------------------
    |
    | Configuration for Azure Face API when used as the facial
    | recognition service. These are used only when service is "azure".
    |
    */

    'azure' => [
        'endpoint' => env('AZURE_FACE_ENDPOINT'),
        'key' => env('AZURE_FACE_KEY'),
        'confidence_threshold' => env('AZURE_FACE_CONFIDENCE_THRESHOLD', 0.7),
    ],

    /*
    |--------------------------------------------------------------------------
    | Local Processing Settings
    |--------------------------------------------------------------------------
    |
    | Configuration for local facial recognition processing.
    | Used only when service is "local".
    |
    */

    'local' => [
        'library' => env('LOCAL_FACE_LIBRARY', 'opencv'), // opencv, dlib, etc.
        'similarity_threshold' => env('LOCAL_FACE_SIMILARITY_THRESHOLD', 70),
        'models_path' => storage_path('app/facial-recognition/models'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Verification Settings
    |--------------------------------------------------------------------------
    |
    | General settings that apply to facial verification regardless
    | of the service being used.
    |
    */

    'verification' => [
        'max_attempts' => env('FACE_VERIFICATION_MAX_ATTEMPTS', 3),
        'cooldown_minutes' => env('FACE_VERIFICATION_COOLDOWN', 30),
        'require_liveness' => env('FACE_VERIFICATION_REQUIRE_LIVENESS', true),
        'liveness_confidence_threshold' => env('FACE_LIVENESS_THRESHOLD', 0.95),
        'store_verification_images' => env('STORE_FACE_VERIFICATION_IMAGES', true),
        'verification_expiry_days' => env('FACE_VERIFICATION_EXPIRY_DAYS', 90),
    ],
    
    /*
    |--------------------------------------------------------------------------
    | Security Settings
    |--------------------------------------------------------------------------
    |
    | Security-related settings for facial recognition features.
    |
    */

    'security' => [
        'encrypt_facial_data' => env('ENCRYPT_FACIAL_DATA', true),
        'log_verification_attempts' => env('LOG_FACE_VERIFICATION_ATTEMPTS', true),
        'officer_review_required' => env('OFFICER_REVIEW_REQUIRED', true),
        'auto_approve_threshold' => env('AUTO_APPROVE_THRESHOLD', 95),
    ],
];