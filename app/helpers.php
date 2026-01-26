<?php

use App\Core\App;
use App\Core\Response;

if (!function_exists('app')) {
    function app(): App
    {
        return App::getInstance();
    }
}

if (!function_exists('view')) {
    function view(string $view, array $data = []): Response
    {
        $viewPath = __DIR__ . '/../views/' . str_replace('.', '/', $view) . '.php';
        
        if (!file_exists($viewPath)) {
            throw new \RuntimeException("Vue non trouvée: {$view}");
        }

        extract($data);
        ob_start();
        require $viewPath;
        $content = ob_get_clean();

        return new Response($content);
    }
}

if (!function_exists('json')) {
    function json(mixed $data, int $status = 200): Response
    {
        $response = new Response(json_encode($data), $status);
        $response->header('Content-Type', 'application/json');
        return $response;
    }
}

if (!function_exists('response')) {
    function response(string $content = '', int $status = 200, array $headers = []): Response
    {
        $response = new Response($content, $status);
        foreach ($headers as $key => $value) {
            $response->header($key, $value);
        }
        return $response;
    }
}

if (!function_exists('logger')) {
    function logger(): object
    {
        // Simple logger mock pour compatibilité
        return new class {
            public function info(string $message, array $context = []): void {}
            public function error(string $message, array $context = []): void {}
            public function warning(string $message, array $context = []): void {}
        };
    }
}
