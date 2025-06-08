<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Default Image Driver
    |--------------------------------------------------------------------------
    |
    | This option controls the default image "driver" that will be used when
    | processing images with Intervention Image. By default, GD is used
    | as it is more widely available, but Imagick offers more features.
    |
    | Supported: "gd", "imagick"
    |
    */

    'driver' => env('IMAGE_DRIVER', 'gd'),

    /*
    |--------------------------------------------------------------------------
    | Image Storage Settings
    |--------------------------------------------------------------------------
    |
    | These options configure where and how images are stored in the system.
    | The disk option controls which filesystem disk is used (as configured
    | in filesystems.php), while the directory options set specific paths.
    |
    */

    'storage' => [
        'disk' => env('IMAGE_STORAGE_DISK', 'public'),
        'profile_photos_directory' => 'profile-photos',
        'documents_directory' => 'verification-documents',
        'facial_captures_directory' => 'facial-captures',
        'omang_photos_directory' => 'omang-photos',
    ],

    /*
    |--------------------------------------------------------------------------
    | Image Processing Settings
    |--------------------------------------------------------------------------
    |
    | These settings control how images are processed when uploaded or 
    | captured in the system. They define quality, dimensions, and other
    | processing parameters.
    |
    */

    'processing' => [
        'quality' => env('IMAGE_QUALITY', 90),
        'max_width' => 1920,
        'max_height' => 1080,
        'thumbnail_width' => 300,
        'thumbnail_height' => 300,
        'facial_capture_width' => 800,
        'facial_capture_height' => 600,
    ],

    /*
    |--------------------------------------------------------------------------
    | Security Settings
    |--------------------------------------------------------------------------
    |
    | These settings help ensure the security of image handling in the
    | application. They control validation, sanitization, and other
    | security measures.
    |
    */

    'security' => [
        'allowed_mime_types' => [
            'image/jpeg',
            'image/png',
            'image/jpg',
        ],
        'max_file_size' => env('MAX_IMAGE_SIZE', 5120), // in KB (5MB)
        'sanitize_filenames' => true,
        'allow_svg' => false,
    ],

    /*
    |--------------------------------------------------------------------------
    | Optimization Settings
    |--------------------------------------------------------------------------
    |
    | These settings control image optimization features. They're used to
    | reduce file size and improve loading performance without significant
    | quality loss.
    |
    */

    'optimization' => [
        'enabled' => env('OPTIMIZE_IMAGES', true),
        'method' => env('IMAGE_OPTIMIZATION_METHOD', 'auto'), // auto, lossy, lossless
    ],
];