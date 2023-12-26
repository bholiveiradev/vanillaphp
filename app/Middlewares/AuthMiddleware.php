<?php

declare(strict_types=1);

namespace App\Middlewares;

use App\Middlewares\Contracts\MiddlewareInterface;
use App\Http\Request;
use App\Http\Response;
use \Closure;

class AuthMiddleware implements MiddlewareInterface
{
    public function handle(Request $request, Response $response, Closure $next): void
    {
        $isAuth = true;
        
        if (!$isAuth) {
            redirect('/');
        }

        $next();
    }
}