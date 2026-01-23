<?php

use Dotenv\Dotenv;

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../app/helpers.php';

$envFile = dirname(__DIR__) . '/.env';
if (file_exists($envFile)) {
    Dotenv::createImmutable(dirname(__DIR__))->safeLoad();
}
