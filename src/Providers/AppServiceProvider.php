<?php

declare(strict_types=1);

namespace App\Providers;

use App\Core\DIContainer;
use App\Core\Http\{Request, Response};
use App\Http\Middlewares\ExampleMiddleware;
use App\Support\Contracts\{CacheInterface, ViewInterface};
use App\Support\CacheRedis;
use App\Support\ViewHtml;

class AppServiceProvider
{
    public static function register(DIContainer $container): void
    {
        $container->bind(Request::class, Request::class);
        $container->bind(Response::class, Response::class);

        $container->bind(ExampleMiddleware::class, ExampleMiddleware::class);

        $container->bind(CacheInterface::class, CacheRedis::class);
        $container->bind(ViewInterface::class, ViewHtml::class);
    }
}
