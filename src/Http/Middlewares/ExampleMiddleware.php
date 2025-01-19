<?php

declare(strict_types=1);

namespace App\Http\Middlewares;

use App\Core\Http\Request;
use App\Core\Http\Response;
use App\Http\Middlewares\Contracts\MiddlewareInterface;
use Closure;

class ExampleMiddleware implements MiddlewareInterface
{
    public function handle(Request $request, Response $response, Closure $next): void
    {
        // Faça algo antes do próximo middleware
        $next();
        // Faça algo depois do próximo middleware
    }
}