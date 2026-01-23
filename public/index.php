<?php

declare(strict_types=1);

use App\Core\Request;
use App\Core\Router;

$app = require_once __DIR__ . '/../bootstrap.php';

$request = Request::capture();
$router = app()->make(Router::class);
$response = $router->dispatch($request);
$response->send();
