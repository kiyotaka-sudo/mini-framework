<?php

namespace App\Core;

use Database\Models\User;

class Auth
{
    protected static ?array $user = null;

    /**
     * Démarre la session si elle n'est pas déjà démarrée
     */
    public static function startSession(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    /**
     * Connecte un utilisateur
     */
    public static function login(array $user): void
    {
        self::startSession();
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user'] = [
            'id' => $user['id'],
            'name' => $user['name'],
            'email' => $user['email'],
            'role' => $user['role'] ?? 'user',
        ];
        self::$user = $user;
    }

    /**
     * Déconnecte l'utilisateur
     */
    public static function logout(): void
    {
        self::startSession();
        unset($_SESSION['user_id'], $_SESSION['user']);
        session_destroy();
        self::$user = null;
    }

    /**
     * Vérifie si l'utilisateur est connecté
     */
    public static function check(): bool
    {
        self::startSession();
        return isset($_SESSION['user_id']);
    }

    /**
     * Récupère l'utilisateur connecté
     */
    public static function user(): ?array
    {
        if (self::$user) {
            return self::$user;
        }

        self::startSession();

        if (!isset($_SESSION['user_id'])) {
            return null;
        }

        self::$user = User::make()->find($_SESSION['user_id']);
        return self::$user;
    }

    /**
     * Récupère l'ID de l'utilisateur connecté
     */
    public static function id(): ?int
    {
        self::startSession();
        return $_SESSION['user_id'] ?? null;
    }

    /**
     * Vérifie si l'utilisateur a un rôle spécifique
     */
    public static function hasRole(string $role): bool
    {
        $user = self::user();
        return $user && isset($user['role']) && $user['role'] === $role;
    }

    /**
     * Vérifie si l'utilisateur est admin
     */
    public static function isAdmin(): bool
    {
        return self::hasRole('admin');
    }

    /**
     * Tente de connecter avec email et mot de passe
     */
    public static function attempt(string $email, string $password): bool
    {
        $user = User::make()->attempt($email, $password);

        if ($user) {
            self::login($user);
            return true;
        }

        return false;
    }
}
