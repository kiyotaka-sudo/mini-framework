# Commandes du mini-framework

## Installation et prérequis
- `composer install` : installe les dépendances du projet à partir de `composer.json`.
- `composer dump-autoload` : régénère les fichiers d'autoloading PSR-4, utile après avoir ajouté de nouvelles classes.
- `php --version` et `composer -V` : vérifient que l'environnement dispose bien de PHP >= 8.1 et de Composer.

## CLI interne (`bin/console`)
- `php bin/console help` : affiche la liste des commandes intégrées et leur usage.
- `php bin/console migrate` : exécute toutes les migrations PHP présentes dans `database/migrations`.
- `php bin/console routes` : liste les routes enregistrées par le routeur (`app/Core/Router.php`).
- `php bin/console serve [host:port]` : lance le serveur PHP intégré (par défaut `localhost:8000`).

## Serveur HTTP
- `php -S localhost:8000 -t public` : démarre manuellement le serveur HTTP de développement en ciblant `public/index.php`.

## Tests et vérifications
- `vendor/bin/phpunit` : exécute la suite PHPUnit définie dans `tests/`.

## Commandes complémentaires
- `php artisan` (non fourni) : inspiré de Laravel, mais substitué ici par `bin/console` pour les tâches CLI.
- Scripts `php bin/console ...` peuvent être combinés avec des options PHP/globales (exemple `php -d memory_limit=512M bin/console migrate`).
