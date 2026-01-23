<?php

use App\Core\Request;
use App\Core\Router;
use App\Http\Controllers\HomeController;
use Database\Models\User;

return function (Router $router): void {
    $router->get('/', HomeController::class . '@index', [
        'middleware' => ['log'],
    ]);

    $router->get('/users/{id}', function (Request $request) {
        $id = (int) $request->route('id');
        $user = User::make()->find($id);

        if (!$user) {
            return response('Utilisateur introuvable', 404);
        }

        return json($user);
    }, [
        'middleware' => ['log'],
    ]);

    $router->get('/api', function (Request $request) {
        return json([
            'status' => 'ok',
            'env' => $_ENV['APP_ENV'] ?? 'production',
            'method' => $request->getMethod(),
        ]);
    });
};
