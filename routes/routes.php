<?php

use App\Controllers\ProductController;
use App\Middlewares\ExampleMiddleware;

$router->get('/', fn () => view('home/index'));

$router->middlewares([ExampleMiddleware::class])
    ->group('/products', function ($router) {
        $router->get('/', [ProductController::class, 'index']);
        $router->get('/create', [ProductController::class, 'create']);
        $router->post('/', [ProductController::class, 'store']);
        $router->get('/{id}', [ProductController::class, 'show']);
        $router->get('/{id}/edit', [ProductController::class, 'edit']);
        $router->put('/{id}', [ProductController::class, 'update']);
        $router->delete('/{id}', [ProductController::class, 'delete']);
    });
