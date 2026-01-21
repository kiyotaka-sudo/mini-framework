<?php

$host = '127.0.0.1';
$port = '3306';
$user = 'root';
$pass = '';

try {
    $pdo = new PDO("mysql:host=$host;port=$port", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    $pdo->exec("CREATE DATABASE IF NOT EXISTS mini_framework CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
    echo "Database 'mini_framework' created successfully (or already exists).\n";
    
} catch (PDOException $e) {
    die("DB Creation failed: " . $e->getMessage());
}
