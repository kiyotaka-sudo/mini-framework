<?php

use App\Core\App;
use App\Core\Database;
use App\Core\Logger;
use App\Core\Router;
use App\Http\Middleware\RequestLoggerMiddleware;
use Dotenv\Dotenv;

require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/app/helpers.php';

$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->safeLoad();

$env = $_ENV['APP_ENV'] ?? 'production';
if ($env === 'local') {
    ini_set('display_errors', '1');
    ini_set('display_startup_errors', '1');
    error_reporting(E_ALL);
} else {
    ini_set('display_errors', '0');
    ini_set('display_startup_errors', '0');
    error_reporting(0);
}

$logPath = $_ENV['LOG_PATH'] ?? __DIR__ . '/storage/logs/app.log';
$_ENV['LOG_PATH'] = $logPath;

$app = new App();
$app->singleton(Logger::class, fn () => new Logger($logPath));
$app->singleton(Database::class, fn () => Database::connectFromEnv());
$app->singleton(Router::class, fn (App $container) => new Router($container));
$app->singleton(RequestLoggerMiddleware::class, fn () => new RequestLoggerMiddleware());

$router = $app->make(Router::class);
$router->aliasMiddleware('log', RequestLoggerMiddleware::class);

$routesPath = __DIR__ . '/routes/web.php';
if (file_exists($routesPath)) {
    $registrar = require $routesPath;
    if (is_callable($registrar)) {
        $registrar($router);
    }
}

logger()->info('Bootstrap terminé.', ['env' => $env]);

return $app;
