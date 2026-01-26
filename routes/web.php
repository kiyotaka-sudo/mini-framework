<?php

use App\Core\Request;
use App\Core\Router;
use App\Http\Controllers\AuthController;

return function (Router $router): void {
    // Page d'accueil - redirige vers login
    $router->get('/', function (Request $request) {
        return response('', 302, ['Location' => '/login']);
    });

    // Pages d'authentification
    $router->get('/login', AuthController::class . '@loginPage');
    $router->get('/register', AuthController::class . '@registerPage');

    // API d'authentification
    $router->post('/api/auth/login', AuthController::class . '@login');
    $router->post('/api/auth/register', AuthController::class . '@register');
    $router->post('/api/auth/logout', AuthController::class . '@logout');
    $router->get('/api/auth/me', AuthController::class . '@me');

    // Dashboard (page protégée simple)
    $router->get('/dashboard', function (Request $request) {
        return view('dashboard', [
            'appName' => $_ENV['APP_NAME'] ?? 'MiniFramework'
        ]);
    });
};
