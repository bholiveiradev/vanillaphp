<?php

declare(strict_types=1);

namespace App\Middlewares;

use App\Middlewares\Contracts\MiddlewareInterface;
use App\Http\Request;
use App\Http\Response;
use \Closure;

class ExampleMiddleware implements MiddlewareInterface
{
    public function handle(Request $request, Response $response, Closure $next): void
    {
        // Faça algo antes do request passar pelo middleware seguinte
        $next();
        // Faça algo depois do request passar pelo middleware seguinte
    }
}