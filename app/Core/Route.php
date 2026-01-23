<?php

namespace App\Core;

use Closure;

class Route
{
    protected string $pattern;

    public function __construct(
        protected string $method,
        protected string $path,
        protected mixed $action,
        protected array $middleware = [],
    ) {
        $this->pattern = $this->buildPattern($this->normalizePath($this->path));
    }

    public function getMethod(): string
    {
        return $this->method;
    }

    public function getAction(): mixed
    {
        return $this->action;
    }

    public function getMiddleware(): array
    {
        return $this->middleware;
    }

    public function setMiddleware(array $middleware): void
    {
        $this->middleware = $middleware;
    }

    public function matches(string $path, array &$parameters = []): bool
    {
        if (!preg_match($this->pattern, $this->normalizePath($path), $matches)) {
            return false;
        }

        foreach ($matches as $key => $value) {
            if (is_string($key)) {
                $parameters[$key] = $value;
            }
        }

        return true;
    }

    protected function normalizePath(string $path): string
    {
        if ($path === '/') {
            return '/';
        }

        return '/' . trim(preg_replace('#/+#', '/', $path), '/');
    }

    protected function buildPattern(string $path): string
    {
        $pattern = preg_replace_callback(
            '#\{([^}/]+)\}#',
            fn ($matches) => '(?P<' . $matches[1] . '>[^/]+)',
            $path === '/' ? '' : $path
        );

        return '#^' . ($pattern === '' ? '/' : $pattern) . '$#';
    }

    public function getPath(): string
    {
        return $this->path;
    }

    public function getActionDescription(): string
    {
        if (is_string($this->action)) {
            return $this->action;
        }

        if (is_array($this->action) && isset($this->action[0], $this->action[1])) {
            return sprintf('%s@%s', $this->action[0], $this->action[1]);
        }

        if ($this->action instanceof Closure) {
            return 'Closure';
        }

        return gettype($this->action);
    }
}
