<?php

namespace App\Http\Middleware;

use App\Core\Middleware\MiddlewareInterface;
use App\Core\Request;
use App\Core\Response;
use Closure;

class AdminAuth implements MiddlewareInterface
{
    public function handle(Request $request, Closure $next): Response
    {
        $envUser = $_ENV['ADMIN_USERNAME'] ?? 'admin';
        $envPass = $_ENV['ADMIN_PASSWORD'] ?? 'secret';

        // Basic Auth check
        $user = $_SERVER['PHP_AUTH_USER'] ?? null;
        $pass = $_SERVER['PHP_AUTH_PW'] ?? null;

        if ($user === $envUser && $pass === $envPass) {
            return $next($request);
        }

        // Failed or no auth
        header('WWW-Authenticate: Basic realm="Admin Area"');
        return response('Unauthorized', 401);
    }
}
