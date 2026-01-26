<?php

namespace App\Http\Controllers;

use App\Core\Request;
use App\Core\Response;

class AuthController
{
    /**
     * Affiche la page de connexion
     */
    public function loginPage(Request $request): Response
    {
        return view('auth/login', [
            'appName' => $_ENV['APP_NAME'] ?? 'MiniFramework',
        ]);
    }

    /**
     * Affiche la page d'inscription
     */
    public function registerPage(Request $request): Response
    {
        return view('auth/register', [
            'appName' => $_ENV['APP_NAME'] ?? 'MiniFramework',
        ]);
    }

    /**
     * Traite la connexion (API)
     * Note: Version simplifiée pour démonstration
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

        // TODO: Implémenter la vraie logique d'authentification avec Auth et User
        // Pour l'instant, on simule une connexion réussie
        return json([
            'success' => true,
            'message' => 'Connexion simulée (Auth non implémenté)',
            'redirect' => '/dashboard'
        ]);
    }

    /**
     * Traite l'inscription (API)
     * Note: Version simplifiée pour démonstration
     */
    public function register(Request $request): Response
    {
        $name = $request->input('name');
        $email = $request->input('email');
        $password = $request->input('password');

        if (empty($name) || empty($email) || empty($password)) {
            return json([
                'success' => false,
                'message' => 'Nom, email et mot de passe requis'
            ], 400);
        }

        if (strlen($password) < 6) {
            return json([
                'success' => false,
                'message' => 'Le mot de passe doit contenir au moins 6 caractères'
            ], 400);
        }

        // TODO: Implémenter la vraie logique d'inscription avec User model
        // Pour l'instant, on simule une inscription réussie
        return json([
            'success' => true,
            'message' => 'Inscription simulée (User model non implémenté)',
            'redirect' => '/dashboard'
        ], 201);
    }

    /**
     * Déconnexion
     */
    public function logout(Request $request): Response
    {
        // TODO: Implémenter Auth::logout()
        return json([
            'success' => true,
            'message' => 'Déconnexion simulée',
            'redirect' => '/login'
        ]);
    }

    /**
     * Récupère l'utilisateur connecté (API)
     */
    public function me(Request $request): Response
    {
        // TODO: Implémenter Auth::check() et Auth::user()
        return json([
            'success' => false,
            'message' => 'Non authentifié (Auth non implémenté)'
        ], 401);
    }
}
