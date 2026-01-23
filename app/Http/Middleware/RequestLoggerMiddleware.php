<?php

namespace App\Http\Middleware;

use App\Core\Middleware\MiddlewareInterface;
use App\Core\Request;
use App\Core\Response;
use Closure;

class RequestLoggerMiddleware implements MiddlewareInterface
{
    public function handle(Request $request, Closure $next): Response
    {
        logger()->debug('Requête entrante', [
            'method' => $request->getMethod(),
            'path' => $request->getPath(),
        ]);

        return $next($request);
    }
}
