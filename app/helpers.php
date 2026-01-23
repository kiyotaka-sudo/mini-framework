<?php

use App\Core\App;
use App\Core\Logger;
use App\Core\Response;

if (!function_exists('app')) {
    function app(): App
    {
        return App::getInstance();
    }
}

if (!function_exists('logger')) {
    function logger(): Logger
    {
        return app()->make(Logger::class);
    }
}

if (!function_exists('response')) {
    function response(string $body = '', int $status = 200, array $headers = []): Response
    {
        return new Response($body, $status, $headers);
    }
}

if (!function_exists('json')) {
    function json(array|object $payload, int $status = 200, array $headers = []): Response
    {
        $headers['Content-Type'] = 'application/json; charset=utf-8';
        $body = json_encode($payload, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);

        return new Response($body ?: '', $status, $headers);
    }
}

if (!function_exists('view')) {
    function view(string $name, array $data = [], ?string $layout = 'layouts/app'): Response
    {
        $base = dirname(__DIR__);
        $render = function (string $path, array $data) {
            if (!file_exists($path)) {
                throw new RuntimeException("Vue introuvable: {$path}");
            }

            extract($data, EXTR_SKIP);
            ob_start();
            include $path;
            return ob_get_clean();
        };

        $viewPath = $base . '/views/' . str_replace('.', '/', $name) . '.php';
        $content = $render($viewPath, $data);

        if ($layout) {
            $layoutPath = $base . '/views/' . str_replace('.', '/', $layout) . '.php';
            if (file_exists($layoutPath)) {
                $content = $render($layoutPath, array_merge($data, ['slot' => $content]));
            }
        }

        return response($content);
    }
}
