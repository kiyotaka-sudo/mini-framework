<?php

// Basic verification script

require_once __DIR__ . '/app/Core/Database.php';
require_once __DIR__ . '/app/Core/Model.php';
require_once __DIR__ . '/database/models/User.php';

// Manually load .env since we might not have composer/vlucas/phpdotenv yet
if (file_exists(__DIR__ . '/.env')) {
    $env = parse_ini_file(__DIR__ . '/.env');
    foreach ($env as $key => $value) {
        $_ENV[$key] = $value;
    }
}

use Database\Models\User;
use App\Core\Database;

try {
    echo "Testing Database Connection...\n";
    $db = Database::getInstance()->getConnection();
    echo "Connection successful!\n";

    echo "\nTesting User Creation...\n";
    $email = 'test_' . time() . '@example.com';
    $user = User::create([
        'name' => 'Test User',
        'email' => $email,
        'password' => 'secret'
    ]);
    
    if ($user && $user['email'] === $email) {
        echo "User created successfully! ID: " . $user['id'] . "\n";
    } else {
        echo "User creation failed.\n";
        exit(1);
    }

    echo "\nTesting User Find...\n";
    $found = User::find($user['id']);
    if ($found && $found['name'] === 'Test User') {
        echo "User found successfully!\n";
    } else {
        echo "User find failed.\n";
        exit(1);
    }
    
    echo "\nEverything looks good!\n";

} catch (Exception $e) {
    echo "Verification failed: " . $e->getMessage() . "\n";
    exit(1);
}
