<?php

namespace App\Core;

class Request
{
    protected string $method;
    protected string $uri;
    protected array $body = [];
    protected array $query = [];
    protected array $headers = [];
    protected array $routeParameters = [];

    private function __construct()
    {
        $this->method = strtoupper($_SERVER['REQUEST_METHOD'] ?? 'GET');
        $this->uri = $_SERVER['REQUEST_URI'] ?? '/';
        $this->query = $_GET;
        $this->body = $_POST;
        $this->headers = $this->collectHeaders();
    }

    public static function capture(): self
    {
        return new self();
    }

    public function getMethod(): string
    {
        return $this->method;
    }

    public function getUri(): string
    {
        return $this->uri;
    }

    public function getPath(): string
    {
        $path = parse_url($this->uri, PHP_URL_PATH) ?: '/';
        if ($path !== '/') {
            $path = '/' . trim(preg_replace('#/+#', '/', $path), '/');
        }

        return $path;
    }

    public function all(): array
    {
        return array_merge($this->body, $this->query);
    }

    public function input(string $key, mixed $default = null): mixed
    {
        return $this->body[$key] ?? $this->query[$key] ?? $default;
    }

    public function query(string $key, mixed $default = null): mixed
    {
        return $this->query[$key] ?? $default;
    }

    public function header(string $key, mixed $default = null): mixed
    {
        $key = strtolower($key);
        return $this->headers[$key] ?? $default;
    }

    public function mergeRouteParameters(array $parameters): void
    {
        $this->routeParameters = array_merge($this->routeParameters, $parameters);
    }

    public function route(string $key, mixed $default = null): mixed
    {
        return $this->routeParameters[$key] ?? $default;
    }

    private function collectHeaders(): array
    {
        $headers = [];

        foreach ($_SERVER as $key => $value) {
            if (str_starts_with($key, 'HTTP_')) {
                $name = strtolower(str_replace('_', '-', substr($key, 5)));
                $headers[$name] = $value;
            }
        }

        return $headers;
    }
}
