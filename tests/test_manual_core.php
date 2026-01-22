<?php

// Manual require for testing without vendor/autoload.php
require_once __DIR__ . '/../app/Http/Request.php';
require_once __DIR__ . '/../app/Http/Response.php';
require_once __DIR__ . '/../app/Core/App.php';
require_once __DIR__ . '/../app/Core/Router.php';
require_once __DIR__ . '/../app/Core/Middleware.php';
require_once __DIR__ . '/../app/Core/Database.php';

use App\Core\App;
use App\Core\Router;
use App\Core\Database;
use App\Http\Request;
use App\Http\Response;

echo "=== TESTS DU NOYAU (CORE) ===\n\n";

// Test 1: App Container
echo "Test 1: App Container (bind/make)\n";
$app = App::getInstance();
$app->bind('key', function() {
    return 'value';
});
try {
    $value = $app->make('key');
    echo "  ✓ PASS: " . ($value === 'value' ? "Bind/Make fonctionne" : "FAIL") . "\n";
} catch (Exception $e) {
    echo "  ✗ FAIL: " . $e->getMessage() . "\n";
}

// Test 2: Router - Route simple
echo "\nTest 2: Router - Route simple\n";
$router = new Router();
$router->get('/test', function() {
    return "Hello World";
});

$_SERVER['REQUEST_METHOD'] = 'GET';
$_SERVER['REQUEST_URI'] = '/test';
$request = new Request([], [], $_SERVER, [], []);
$response = $router->resolve($request);
ob_start();
$response->send();
$output = ob_get_clean();
echo "  " . ($output === 'Hello World' ? "✓ PASS" : "✗ FAIL") . ": Route simple\n";

// Test 3: Router - Route dynamique
echo "\nTest 3: Router - Route avec paramètres dynamiques\n";
$router->get('/users/{id}', function($id) {
    return "User: " . $id;
});

$_SERVER['REQUEST_METHOD'] = 'GET';
$_SERVER['REQUEST_URI'] = '/users/42';
$request = new Request([], [], $_SERVER, [], []);
$response = $router->resolve($request);
ob_start();
$response->send();
$output = ob_get_clean();
echo "  " . ($output === 'User: 42' ? "✓ PASS" : "✗ FAIL") . ": Paramètres dynamiques\n";

// Test 4: Database - Configuration
echo "\nTest 4: Database - Configuration\n";
try {
    // Test avec SQLite en mémoire pour éviter les dépendances MySQL
    $db = new Database([
        'driver' => 'sqlite',
        'database' => ':memory:',
        'options' => [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ]
    ]);
    
    $pdo = $db->connect();
    echo "  ✓ PASS: Connexion SQLite établie\n";
    
    // Test 5: Database - Requête
    echo "\nTest 5: Database - Exécution de requêtes\n";
    $db->execute("CREATE TABLE test (id INTEGER PRIMARY KEY, name TEXT)");
    $db->execute("INSERT INTO test (name) VALUES (?)", ['Alice']);
    $result = $db->fetch("SELECT * FROM test WHERE name = ?", ['Alice']);
    
    if ($result && $result['name'] === 'Alice') {
        echo "  ✓ PASS: Requêtes SQL fonctionnent\n";
    } else {
        echo "  ✗ FAIL: Problème avec les requêtes\n";
    }
    
    // Test 6: Database - Transactions
    echo "\nTest 6: Database - Transactions\n";
    $db->beginTransaction();
    $db->execute("INSERT INTO test (name) VALUES (?)", ['Bob']);
    $db->commit();
    
    $count = count($db->fetchAll("SELECT * FROM test"));
    echo "  " . ($count === 2 ? "✓ PASS" : "✗ FAIL") . ": Transactions\n";
    
} catch (Exception $e) {
    echo "  ✗ FAIL: " . $e->getMessage() . "\n";
}

echo "\n=== FIN DES TESTS ===\n";
