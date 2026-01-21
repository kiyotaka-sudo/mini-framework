<?php

// Front Controller
// Toutes les requetes passent par ici.

// 1. Initialisation (Bootstrap)
require_once __DIR__ . '/../bootstrap.php';

// 2. Test simple pour verifier que tout fonctionne
echo "<h1>Mini-Framework Working!</h1>";
echo "<p>Environment: " . ($_ENV['APP_ENV'] ?? 'unknown') . "</p>";

// (Futur) Dispatcher la requete via le Router
// $app = new App\Core\App();
// $app->run();
