<?php

// Manual require for testing without vendor/autoload.php
require_once __DIR__ . '/../app/Http/Request.php';
require_once __DIR__ . '/../app/Http/Response.php';
require_once __DIR__ . '/../app/Core/App.php';
require_once __DIR__ . '/../app/Core/Router.php';
require_once __DIR__ . '/../app/Core/Middleware.php'; // Added Middleware interface file

use App\Core\App;
use App\Core\Router;
use App\Http\Request;
use App\Http\Response;

// Test App Container
$app = App::getInstance();
$app->bind('key', function () {
    return 'value';
});
try {
    $value = $app->make('key');
    echo "App Bind/Make: " . ($value === 'value' ? "PASS" : "FAIL") . "\n";
} catch (Exception $e) {
    echo "App Bind/Make: FAIL (" . $e->getMessage() . ")\n";
}

// Test Router
$router = new Router();
$router->get('/test', function () {
    return "Hello World";
});

$router->get('/users/{id}', function ($id) {
    return "User: " . $id;
});

// Mock Request 1: Simple Route
// Need to mock server array for Request::capture-style usage or manual init
$_SERVER['REQUEST_METHOD'] = 'GET';
$_SERVER['REQUEST_URI'] = '/test';
$query = [];
$requestParams = [];
$server = $_SERVER;
$cookies = [];
$files = [];

$request = new Request($query, $requestParams, $server, $cookies, $files);
$response = $router->resolve($request);
ob_start();
$response->send();
$output = ob_get_clean();
echo "Router Simple Route: " . ($output === 'Hello World' ? "PASS" : "FAIL") . "\n";

// Mock Request 2: Dynamic Route
$_SERVER['REQUEST_METHOD'] = 'GET';
$_SERVER['REQUEST_URI'] = '/users/42';
$server = $_SERVER;
$request = new Request($query, $requestParams, $server, $cookies, $files);
$response = $router->resolve($request);
ob_start();
$response->send();
$output = ob_get_clean();
echo "Router Dynamic Route: " . ($output === 'User: 42' ? "PASS" : "FAIL") . "\n";
