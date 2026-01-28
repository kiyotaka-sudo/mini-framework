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
use App\Http\Controllers\ProduitController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\FifaController;
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

    // ============================================
    // ENTITIES GENERATED ROUTES
    // ============================================

    // Produit
    $router->get('/admin/produit', ProduitController::class . '@index', ['middleware' => ['log']]);
    $router->get('/admin/produit/create', ProduitController::class . '@create', ['middleware' => ['log']]);
    $router->get('/admin/produit/{id}/edit', ProduitController::class . '@edit', ['middleware' => ['log']]);
    $router->get('/api/produit', ProduitController::class . '@index', ['middleware' => ['log']]);
    $router->post('/api/produit', ProduitController::class . '@store', ['middleware' => ['log']]);
    $router->get('/api/produit/{id}', ProduitController::class . '@show', ['middleware' => ['log']]);
    $router->put('/api/produit/{id}', ProduitController::class . '@update', ['middleware' => ['log']]);
    $router->delete('/api/produit/{id}', ProduitController::class . '@destroy', ['middleware' => ['log']]);

    // Client
    $router->get('/admin/client', ClientController::class . '@index', ['middleware' => ['log']]);
    $router->get('/admin/client/create', ClientController::class . '@create', ['middleware' => ['log']]);
    $router->get('/admin/client/{id}/edit', ClientController::class . '@edit', ['middleware' => ['log']]);
    $router->get('/api/client', ClientController::class . '@index', ['middleware' => ['log']]);
    $router->post('/api/client', ClientController::class . '@store', ['middleware' => ['log']]);
    $router->get('/api/client/{id}', ClientController::class . '@show', ['middleware' => ['log']]);
    $router->put('/api/client/{id}', ClientController::class . '@update', ['middleware' => ['log']]);
    $router->delete('/api/client/{id}', ClientController::class . '@destroy', ['middleware' => ['log']]);

    // Fifa
    $router->get('/admin/fifa', FifaController::class . '@index', ['middleware' => ['log']]);
    $router->get('/admin/fifa/create', FifaController::class . '@create', ['middleware' => ['log']]);
    $router->get('/admin/fifa/{id}/edit', FifaController::class . '@edit', ['middleware' => ['log']]);
    $router->get('/api/fifa', FifaController::class . '@index', ['middleware' => ['log']]);
    $router->post('/api/fifa', FifaController::class . '@store', ['middleware' => ['log']]);
    $router->get('/api/fifa/{id}', FifaController::class . '@show', ['middleware' => ['log']]);
    $router->put('/api/fifa/{id}', FifaController::class . '@update', ['middleware' => ['log']]);
    $router->delete('/api/fifa/{id}', FifaController::class . '@destroy', ['middleware' => ['log']]);

    // ============================================
    // VoitureController Routes
    // ============================================
    
    // Admin pages
    $router->get('/admin/voiture', VoitureController::class . '@index');
    $router->get('/admin/voiture/create', VoitureController::class . '@create');
    $router->get('/admin/voiture/{id}/edit', VoitureController::class . '@edit');
    
    // API endpoints
    $router->get('/api/voiture', VoitureController::class . '@index');
    $router->post('/api/voiture', VoitureController::class . '@store');
    $router->get('/api/voiture/{id}', VoitureController::class . '@show');
    $router->put('/api/voiture/{id}', VoitureController::class . '@update');
    $router->delete('/api/voiture/{id}', VoitureController::class . '@destroy');

    // ============================================
    // ArtistController Routes
    // ============================================
    
    // Admin pages
    $router->get('/admin/artist', ArtistController::class . '@index');
    $router->get('/admin/artist/create', ArtistController::class . '@create');
    $router->get('/admin/artist/{id}/edit', ArtistController::class . '@edit');
    
    // API endpoints
    $router->get('/api/artist', ArtistController::class . '@index');
    $router->post('/api/artist', ArtistController::class . '@store');
    $router->get('/api/artist/{id}', ArtistController::class . '@show');
    $router->put('/api/artist/{id}', ArtistController::class . '@update');
    $router->delete('/api/artist/{id}', ArtistController::class . '@destroy');

    // ============================================
    // AlbumController Routes
    // ============================================
    
    // Admin pages
    $router->get('/admin/album', AlbumController::class . '@index');
    $router->get('/admin/album/create', AlbumController::class . '@create');
    $router->get('/admin/album/{id}/edit', AlbumController::class . '@edit');
    
    // API endpoints
    $router->get('/api/album', AlbumController::class . '@index');
    $router->post('/api/album', AlbumController::class . '@store');
    $router->get('/api/album/{id}', AlbumController::class . '@show');
    $router->put('/api/album/{id}', AlbumController::class . '@update');
    $router->delete('/api/album/{id}', AlbumController::class . '@destroy');
};
