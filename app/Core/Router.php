<?php

namespace App\Core;

use App\Core\Middleware\MiddlewareInterface;
use Closure;
use Exception;
use App\Core\Request;
use App\Core\Response;
use App\Core\Route;

class Router
{
    protected array $routes = [];
    protected array $middlewareAliases = [];
    protected array $globalMiddleware = [];

    public function __construct(protected App $app)
    {
    }

    public function get(string $path, mixed $action, array $options = []): Route
    {
        return $this->addRoute('GET', $path, $action, $options);
    }

    public function post(string $path, mixed $action, array $options = []): Route
    {
        return $this->addRoute('POST', $path, $action, $options);
    }

    public function aliasMiddleware(string $alias, string $middleware): void
    {
        $this->middlewareAliases[$alias] = $middleware;
    }

    public function useMiddleware(string ...$aliases): void
    {
        $this->globalMiddleware = array_merge($this->globalMiddleware, $aliases);
    }

    protected function addRoute(string $method, string $path, mixed $action, array $options): Route
    {
        $route = new Route($method, $path, $action, $options['middleware'] ?? []);
        $this->routes[$method][] = $route;
        return $route;
    }

    public function dispatch(Request $request): Response
    {
        try {
            [$route, $params] = $this->findRoute($request);
            if (!$route) {
                return $this->handleNotFound($params);
            }

            $middleware = array_merge(
                $this->resolveMiddleware($this->globalMiddleware),
                $this->resolveMiddleware($route->getMiddleware())
            );

            $request->mergeRouteParameters($params);

            $action = $this->resolveAction($route->getAction());
            $pipeline = $this->buildPipeline($middleware, $action);

            return $pipeline($request);
        } catch (Exception $exception) {
            logger()->error('Erreur de dispatch', ['exception' => $exception->getMessage()]);
            return response('Erreur interne', 500);
        }
    }

    protected function findRoute(Request $request): array
    {
        $method = $request->getMethod();
        $path = $request->getPath();
        $allowed = [];

        foreach ($this->routes as $routeMethod => $definitions) {
            foreach ($definitions as $route) {
                $params = [];
                if (!$route->matches($path, $params)) {
                    continue;
                }

                $allowed[] = $routeMethod;
                if ($routeMethod !== $method) {
                    continue;
                }

                return [$route, $params];
            }
        }

        return [null, array_unique($allowed)];
    }

    protected function handleNotFound(array $allowed): Response
    {
        if ($allowed) {
            return response('Méthode non autorisée', 405);
        }

        return response('Page non trouvée', 404);
    }

    protected function resolveMiddleware(array $aliases): array
    {
        return array_map(fn (string $alias) => $this->middlewareAliases[$alias] ?? $alias, $aliases);
    }

    protected function buildPipeline(array $middleware, Closure $action): Closure
    {
        $pipeline = array_reduce(
            array_reverse($middleware),
            fn (Closure $next, string $middlewareClass) => function (Request $request) use ($next, $middlewareClass) {
                $instance = $this->app->make($middlewareClass);
                if (!$instance instanceof MiddlewareInterface) {
                    throw new Exception(sprintf('Le middleware %s doit implémenter MiddlewareInterface.', $middlewareClass));
                }

                return $instance->handle($request, $next);
            },
            $action
        );

        return $pipeline;
    }

    protected function resolveAction(mixed $action): Closure
    {
        if ($action instanceof Closure || is_callable($action)) {
            return fn (Request $request) => $this->toResponse(call_user_func($action, $request));
        }

        if (is_string($action) && str_contains($action, '@')) {
            [$controller, $method] = explode('@', $action, 2);
            return fn (Request $request) => $this->toResponse($this->callController($controller, $method, $request));
        }

        if (is_array($action) && isset($action[0], $action[1])) {
            return fn (Request $request) => $this->toResponse($this->callController($action[0], $action[1], $request));
        }

        throw new Exception('Action de route invalide');
    }

    protected function callController(string $controller, string $method, Request $request): mixed
    {
        $instance = $this->app->make($controller);
        return $instance->{$method}($request);
    }

    protected function toResponse(mixed $result): Response
    {
        if ($result instanceof Response) {
            return $result;
        }

        if (is_array($result)) {
            return json($result);
        }

        return response((string) $result);
    }

    public function describeRoutes(): array
    {
        $list = [];

        foreach ($this->routes as $method => $definitions) {
            foreach ($definitions as $route) {
                $list[] = [
                    'method' => $method,
                    'path' => $route->getPath(),
                    'action' => $route->getActionDescription(),
                    'middleware' => $route->getMiddleware(),
                ];
            }
        }

        return $list;
    }
}
