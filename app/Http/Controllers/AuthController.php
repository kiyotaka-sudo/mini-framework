<?php

namespace App\Http\Controllers;

use App\Core\Auth;
use App\Core\Request;
use App\Core\Response;
use Database\Models\User;

class AuthController
{
    /**
     * Affiche la page de connexion
     */
    public function loginPage(Request $request): Response
    {
        if (Auth::check()) {
            return response('', 302, ['Location' => '/dashboard']);
        }

        return view('auth/login', [
            'appName' => $_ENV['APP_NAME'] ?? 'MiniFramework',
        ]);
    }

    /**
     * Affiche la page d'inscription
     */
    public function registerPage(Request $request): Response
    {
        if (Auth::check()) {
            return response('', 302, ['Location' => '/dashboard']);
        }

        return view('auth/register', [
            'appName' => $_ENV['APP_NAME'] ?? 'MiniFramework',
        ]);
    }

    /**
     * Traite la connexion (API)
     */
    public function login(Request $request): Response
    {
        $email = $request->input('email');
        $password = $request->input('password');

        if (empty($email) || empty($password)) {
            return json([
                'success' => false,
                'message' => 'Email et mot de passe requis'
            ], 400);
        }

        if (Auth::attempt($email, $password)) {
            $user = Auth::user();
            return json([
                'success' => true,
                'message' => 'Connexion reussie',
                'user' => [
                    'id' => $user['id'],
                    'name' => $user['name'],
                    'email' => $user['email'],
                    'role' => $user['role'] ?? 'user'
                ],
                'redirect' => '/dashboard'
            ]);
        }

        return json([
            'success' => false,
            'message' => 'Email ou mot de passe incorrect'
        ], 401);
    }

    /**
     * Traite l'inscription (API)
     */
    public function register(Request $request): Response
    {
        $data = $request->all();

        // Validation
        if (empty($data['name']) || empty($data['email']) || empty($data['password'])) {
            return json([
                'success' => false,
                'message' => 'Nom, email et mot de passe requis'
            ], 400);
        }

        if (strlen($data['password']) < 6) {
            return json([
                'success' => false,
                'message' => 'Le mot de passe doit contenir au moins 6 caracteres'
            ], 400);
        }

        $model = User::make();

        // Vérifier si l'email existe déjà
        if ($model->findByEmail($data['email'])) {
            return json([
                'success' => false,
                'message' => 'Cet email est deja utilise'
            ], 409);
        }

        // Créer l'utilisateur
        $user = $model->register([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => $data['password'],
            'phone' => $data['phone'] ?? null,
            'role' => 'user',
            'status' => 'active'
        ]);

        // Connecter automatiquement
        Auth::login($user);

        return json([
            'success' => true,
            'message' => 'Inscription reussie',
            'user' => [
                'id' => $user['id'],
                'name' => $user['name'],
                'email' => $user['email']
            ],
            'redirect' => '/dashboard'
        ], 201);
    }

    /**
     * Déconnexion
     */
    public function logout(Request $request): Response
    {
        Auth::logout();

        // Si c'est une requête API
        if ($request->header('accept') === 'application/json' ||
            $request->header('content-type') === 'application/json') {
            return json([
                'success' => true,
                'message' => 'Deconnexion reussie',
                'redirect' => '/login'
            ]);
        }

        return response('', 302, ['Location' => '/login']);
    }

    /**
     * Récupère l'utilisateur connecté (API)
     */
    public function me(Request $request): Response
    {
        if (!Auth::check()) {
            return json([
                'success' => false,
                'message' => 'Non authentifie'
            ], 401);
        }

        $user = Auth::user();

        return json([
            'success' => true,
            'user' => [
                'id' => $user['id'],
                'name' => $user['name'],
                'email' => $user['email'],
                'phone' => $user['phone'] ?? null,
                'role' => $user['role'] ?? 'user',
                'status' => $user['status'] ?? 'active'
            ]
        ]);
    }
}
