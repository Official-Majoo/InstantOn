<?php
// check-provider.php

// Place this file in your project root and run it with:
// php check-provider.php

$interventionPath = __DIR__ . '/vendor/intervention/image';

if (!is_dir($interventionPath)) {
    echo "Intervention Image package directory not found at: $interventionPath\n";
    exit(1);
}

// Check for different potential provider locations
$potentialProviders = [
    '/src/Intervention/Image/ImageServiceProvider.php',
    '/src/ImageServiceProvider.php',
    '/src/Intervention/Image/ImageServiceProviderLaravelRecent.php',
    '/src/Intervention/Image/ImageServiceProviderLaravel.php',
    '/src/ImageServiceProviderLaravel.php',
    '/src/Intervention/Image/Provider/LaravelServiceProvider.php',
];

$foundProviders = [];

foreach ($potentialProviders as $providerPath) {
    $fullPath = $interventionPath . $providerPath;
    if (file_exists($fullPath)) {
        echo "Found provider at: $fullPath\n";
        
        // Extract namespace and class name
        $content = file_get_contents($fullPath);
        if (preg_match('/namespace\s+([^;]+)/i', $content, $namespaceMatches) &&
            preg_match('/class\s+(\w+)/i', $content, $classMatches)) {
            $namespace = $namespaceMatches[1];
            $className = $classMatches[1];
            $fullClassName = $namespace . '\\' . $className;
            
            echo "Class name: $fullClassName\n";
            $foundProviders[] = $fullClassName;
        }
    }
}

if (empty($foundProviders)) {
    echo "No provider files found. Your intervention/image package may be corrupted or incomplete.\n";
    echo "Try running: composer remove intervention/image && composer require intervention/image\n";
} else {
    echo "\nAdd one of these providers to config/app.php 'providers' array:\n";
    foreach ($foundProviders as $provider) {
        echo "$provider::class,\n";
    }
}