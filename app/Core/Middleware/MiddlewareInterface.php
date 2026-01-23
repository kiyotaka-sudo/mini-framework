<?php

namespace App\Core\Middleware;

use App\Core\Request;
use App\Core\Response;
use Closure;

interface MiddlewareInterface
{
    public function handle(Request $request, Closure $next): Response;
}
