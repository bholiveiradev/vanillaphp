<?php

declare(strict_types=1);

namespace App\Http\Middlewares\Contracts;

use App\Core\Http\Request;
use App\Core\Http\Response;
use Closure;

interface MiddlewareInterface
{
    public function handle(Request $request, Response $response, Closure $next): void;
}
