<?php

namespace Tests;

use App\Core\App;
use App\Core\Database;
use App\Core\Logger;
use App\Core\Migrator;
use App\Core\Request;
use App\Core\Router;
use PHPUnit\Framework\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    protected App $app;
    protected Router $router;
    protected Database $database;

    protected function setUp(): void
    {
        parent::setUp();

        $_ENV['APP_ENV'] = 'testing';
        $_ENV['LOG_PATH'] = sys_get_temp_dir() . '/mini-framework-test.log';
        $_ENV['DB_CONNECTION'] = 'sqlite';
        $_ENV['DB_DATABASE'] = $this->prepareTestDatabase();

        $this->app = new App();
        $this->app->singleton(Logger::class, fn () => new Logger($_ENV['LOG_PATH']));
        $this->app->singleton(Database::class, fn () => Database::connectFromEnv());
        $this->app->singleton(Router::class, fn (App $container) => new Router($container));

        $this->router = $this->app->make(Router::class);
        $this->database = $this->app->make(Database::class);
    }

    protected function makeRequest(string $method, string $uri): Request
    {
        $_SERVER['REQUEST_METHOD'] = $method;
        $_SERVER['REQUEST_URI'] = $uri;
        $_GET = [];
        $_POST = [];

        return Request::capture();
    }

    protected function runMigrations(): void
    {
        $migrator = new Migrator($this->database, dirname(__DIR__) . '/database/migrations');
        $migrator->run();
    }

    protected function prepareTestDatabase(): string
    {
        $path = sys_get_temp_dir() . '/mini-framework-test.sqlite';
        if (file_exists($path)) {
            unlink($path);
        }

        return $path;
    }
}
