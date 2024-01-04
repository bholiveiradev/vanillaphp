<?php

use App\Core\Http\Router;
use App\Http\Controllers\Api\ProductController as ProductApiController;
use App\Http\Controllers\ProductController;
use App\Http\Middlewares\ExampleMiddleware;

Router::get('/', fn () => view('home/index'));

Router::middlewares([ExampleMiddleware::class])
    ->group('/products', function () {
        Router::get('/', [ProductController::class, 'index']);
        Router::get('/create', [ProductController::class, 'create']);
        Router::post('/', [ProductController::class, 'store']);
        Router::get('/{id}', [ProductController::class, 'show']);
        Router::get('/{id}/edit', [ProductController::class, 'edit']);
        Router::put('/{id}', [ProductController::class, 'update']);
        Router::delete('/{id}', [ProductController::class, 'delete']);
    });

    Router::group('/api', function () {
        Router::get('/products', [ProductApiController::class, 'index']);
        Router::post('/products', [ProductApiController::class, 'store']);
        Router::get('/products/{id}', [ProductApiController::class, 'show']);
        Router::put('/products/{id}', [ProductApiController::class, 'update']);
        Router::delete('/products/{id}', [ProductApiController::class, 'delete']);
    });