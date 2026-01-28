<?php

ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/vendor/autoload.php';

try {
    $app = require_once __DIR__ . '/bootstrap.php';
    
    echo "Bootstrap successful.\n";
    
    // Simulate request to /
    $router = $app->make(\App\Core\Router::class);
    
    echo "Router loaded.\n";
    
    // Test view rendering manually
    if (!function_exists('view')) {
         require_once __DIR__ . '/app/helpers.php';
    }
    
    echo "Attempting to render home view...\n";
    
    $response = view('home', [
        'title' => 'Debug Home',
        'isNewProject' => true,
        'appName' => 'MiniFramework'
    ]);
    
    echo "View rendered successfully.\n";
    
} catch (Throwable $e) {
    echo "CAUGHT EXCEPTION: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . " on line " . $e->getLine() . "\n";
    echo "Trace:\n" . $e->getTraceAsString() . "\n";
}
