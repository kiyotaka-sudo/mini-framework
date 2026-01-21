<?php

use Dotenv\Dotenv;

// 1. Charger l'autoloader de Composer
// Cela permet de charger automatiquement les classes dans app/ et database/
// ainsi que les dependances (comme phpdotenv)
require_once __DIR__ . '/vendor/autoload.php';

// 2. Charger les variables d'environnement (.env)
// On utilise vlucas/phpdotenv pour lire le fichier .env a la racine
$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->safeLoad();

// 3. Gestion des erreurs
// Si on est en local, on affiche tout. Sinon on cache pour la prod.
$env = $_ENV['APP_ENV'] ?? 'production';

if ($env === 'local') {
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
} else {
    ini_set('display_errors', 0);
    ini_set('display_startup_errors', 0);
    error_reporting(0);
}

// 4. (Futur) Initialiser le conteneur de services (App) ici
