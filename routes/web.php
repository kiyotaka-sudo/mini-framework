<?php

use App\Core\Request;
use App\Core\Router;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\BackupController;
use App\Http\Controllers\BuilderController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\PageBuilderController;
use Database\Models\User;





return function (Router $router): void {
    // ============================================
    // PAGES PUBLIQUES
    // ============================================

    // Admin Dashboard
    $router->get('/admin', AdminController::class . '@index', [
        'middleware' => ['log'],
    ]);

    // Visual Project Builder

    $router->get('/builder', BuilderController::class . '@index', [
        'middleware' => ['log'],
    ]);
    
    $router->post('/builder/generate', BuilderController::class . '@generate', [
        'middleware' => ['log'],
    ]);
    
    $router->post('/builder/preview', BuilderController::class . '@preview', [
        'middleware' => ['log'],
    ]);

    // Visual Page Builder (Drag & Drop)
    $router->get('/page-builder', PageBuilderController::class . '@index', [
        'middleware' => ['log'],
    ]);
    
    $router->post('/page-builder/save', PageBuilderController::class . '@save', [
        'middleware' => ['log'],
    ]);

    $router->get('/view-page/{name}', function(Request $request) {
        $name = $request->route('name');
        return view('pages.' . $name, ['title' => ucfirst($name)]);
    }, [
        'middleware' => ['log'],
    ]);

    // Page d'accueil


    $router->get('/', function() {
        return view('home', [
            'title' => 'Bienvenue dans votre nouveau projet',
            'isNewProject' => true
        ]);
    }, [
        'middleware' => ['log'],
    ]);



    // Page de gestion des utilisateurs
    $router->get('/users', HomeController::class . '@users', [
        'middleware' => ['log'],
    ]);

    // Page de gestion des tâches
    $router->get('/tasks', HomeController::class . '@tasks', [
        'middleware' => ['log'],
    ]);

    // Dashboard
    $router->get('/dashboard', HomeController::class . '@dashboard', [
        'middleware' => ['log'],
    ]);


    // ============================================
    // AUTHENTIFICATION - PAGES
    // ============================================

    $router->get('/login', AuthController::class . '@loginPage', [
        'middleware' => ['log'],
    ]);

    $router->get('/register', AuthController::class . '@registerPage', [
        'middleware' => ['log'],
    ]);

    // ============================================
    // AUTHENTIFICATION - API
    // ============================================

    $router->post('/api/auth/login', AuthController::class . '@login', [
        'middleware' => ['log'],
    ]);

    $router->post('/api/auth/register', AuthController::class . '@register', [
        'middleware' => ['log'],
    ]);

    $router->post('/api/auth/logout', AuthController::class . '@logout', [
        'middleware' => ['log'],
    ]);

    $router->get('/api/auth/me', AuthController::class . '@me', [
        'middleware' => ['log'],
    ]);

    // ============================================
    // API STATUS
    // ============================================

    $router->get('/api', function (Request $request) {
        return json([
            'status' => 'ok',
            'env' => $_ENV['APP_ENV'] ?? 'production',
            'method' => $request->getMethod(),
            'endpoints' => [
                'users' => '/api/users',
                'tasks' => '/api/tasks',
                'auth' => '/api/auth/login',
                'backups' => '/api/backups'
            ]
        ]);
    });

    // ============================================
    // ROUTES CRUD UTILISATEURS
    // ============================================

    // GET /api/users - Liste avec recherche et pagination
    // Params: ?search=xxx&role=xxx&status=xxx&page=1&per_page=10
    $router->get('/api/users', UserController::class . '@index', [
        'middleware' => ['log'],
    ]);

    // GET /api/users/{id} - Affiche un utilisateur
    $router->get('/api/users/{id}', UserController::class . '@show', [
        'middleware' => ['log'],
    ]);

    // POST /api/users - Crée un utilisateur
    $router->post('/api/users', UserController::class . '@store', [
        'middleware' => ['log'],
    ]);

    // PUT /api/users/{id} - Met à jour un utilisateur
    $router->put('/api/users/{id}', UserController::class . '@update', [
        'middleware' => ['log'],
    ]);

    // DELETE /api/users/{id} - Supprime un utilisateur
    $router->delete('/api/users/{id}', UserController::class . '@destroy', [
        'middleware' => ['log'],
    ]);

    // PATCH /api/users/{id}/status - Change le statut
    $router->patch('/api/users/{id}/status', UserController::class . '@changeStatus', [
        'middleware' => ['log'],
    ]);

    // PATCH /api/users/{id}/role - Change le rôle
    $router->patch('/api/users/{id}/role', UserController::class . '@changeRole', [
        'middleware' => ['log'],
    ]);

    // ============================================
    // ROUTES CRUD TÂCHES
    // ============================================

    // GET /api/tasks - Liste avec recherche et pagination
    // Params: ?search=xxx&status=xxx&priority=xxx&user_id=xxx&page=1&per_page=10
    $router->get('/api/tasks', TaskController::class . '@index', [
        'middleware' => ['log'],
    ]);

    // GET /api/tasks/stats - Statistiques
    $router->get('/api/tasks/stats', TaskController::class . '@stats', [
        'middleware' => ['log'],
    ]);

    // GET /api/tasks/trash - Corbeille
    $router->get('/api/tasks/trash', TaskController::class . '@trash', [
        'middleware' => ['log'],
    ]);

    // GET /api/tasks/{id} - Affiche une tâche
    $router->get('/api/tasks/{id}', TaskController::class . '@show', [
        'middleware' => ['log'],
    ]);

    // POST /api/tasks - Crée une tâche
    $router->post('/api/tasks', TaskController::class . '@store', [
        'middleware' => ['log'],
    ]);

    // PUT /api/tasks/{id} - Met à jour une tâche
    $router->put('/api/tasks/{id}', TaskController::class . '@update', [
        'middleware' => ['log'],
    ]);

    // DELETE /api/tasks/{id} - Soft delete
    $router->delete('/api/tasks/{id}', TaskController::class . '@destroy', [
        'middleware' => ['log'],
    ]);

    // DELETE /api/tasks/{id}/force - Suppression définitive
    $router->delete('/api/tasks/{id}/force', TaskController::class . '@forceDelete', [
        'middleware' => ['log'],
    ]);

    // POST /api/tasks/{id}/restore - Restaurer
    $router->post('/api/tasks/{id}/restore', TaskController::class . '@restore', [
        'middleware' => ['log'],
    ]);

    // PATCH /api/tasks/{id}/status - Changer statut
    $router->patch('/api/tasks/{id}/status', TaskController::class . '@changeStatus', [
        'middleware' => ['log'],
    ]);

    // ============================================
    // ROUTES BACKUP / SAUVEGARDE
    // ============================================

    $router->get('/api/backups', BackupController::class . '@index', [
        'middleware' => ['log'],
    ]);

    $router->post('/api/backups', BackupController::class . '@store', [
        'middleware' => ['log'],
    ]);

    $router->post('/api/backups/all', BackupController::class . '@backupAll', [
        'middleware' => ['log'],
    ]);

    $router->get('/api/backups/export/{table}', BackupController::class . '@export', [
        'middleware' => ['log'],
    ]);

    $router->post('/api/backups/restore', BackupController::class . '@restore', [
        'middleware' => ['log'],
    ]);

    $router->delete('/api/backups/{filename}', BackupController::class . '@destroy', [
        'middleware' => ['log'],
    ]);

    // ============================================
    // ROUTE LEGACY
    // ============================================

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
};
