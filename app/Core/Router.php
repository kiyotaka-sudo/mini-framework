<?php

namespace App\Core;

use App\Http\Request;
use App\Http\Response;

class Router
{
    protected array $routes = [];

    public function get(string $path, callable|array $callback): void
    {
        $this->routes['GET'][$path] = $callback;
    }

    public function post(string $path, callable|array $callback): void
    {
        $this->routes['POST'][$path] = $callback;
    }

    protected array $globalMiddleware = [];

    public function use(string|callable $middleware): void
    {
        $this->globalMiddleware[] = $middleware;
    }

    public function resolve(Request $request): Response
    {
        $method = $request->method();
        $path = $request->uri();
        $callback = $this->routes[$method][$path] ?? false;
        $params = [];

        if (!$callback) {
            $matched = false;
            foreach ($this->routes[$method] ?? [] as $routePath => $handler) {
                $pattern = preg_replace('/\{[a-zA-Z0-9_]+\}/', '([^/]+)', $routePath);
                if (preg_match("#^$pattern$#", $path, $matches)) {
                    array_shift($matches);
                    $callback = $handler;
                    $params = $matches;
                    $matched = true;
                    break;
                }
            }
            
            if (!$matched) {
                return new Response("Not Found", 404);
            }
        }

        // Middleware Pipeline
        $pipeline = array_reduce(
            array_reverse($this->globalMiddleware),
            function ($next, $middleware) {
                return function ($request) use ($next, $middleware) {
                    if (is_string($middleware) && class_exists($middleware)) {
                        $middleware = new $middleware;
                    }
                    
                    if ($middleware instanceof Middleware) {
                        return $middleware->handle($request, $next);
                    }
                    
                    // Callable middleware
                    return $middleware($request, $next);
                };
            },
            function ($request) use ($callback, $params) {
                return $this->invoke($callback, $params);
            }
        );

        return $pipeline($request);
    }

    protected function invoke(callable|array $callback, array $params = []): Response
    {
        if (is_array($callback)) {
            [$controller, $method] = $callback;
            $controller = new $controller;
            $callback = [$controller, $method];
        }

        $result = call_user_func_array($callback, $params);

        if ($result instanceof Response) {
            return $result;
        }

        return new Response((string) $result);
    }
}
