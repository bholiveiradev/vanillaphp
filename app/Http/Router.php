<?php

declare(strict_types=1);

namespace App\Http;

use Closure;

class Router
{
    private array    $routes = [];
    private array    $middlewares = [];
    private ?string  $prefix = null;

    private function addRoute(string $method, string $uri, mixed $handler, array $middlewares): void
    {
        $this->routes[] = [
            'method' => $method,
            'uri' => $this->prefix . rtrim($uri, '/') . '/',
            'handler' => $handler,
            'middlewares' => array_merge($this->middlewares, $middlewares),
        ];
    }

    private function addMiddlewares(array $middlewares): void
    {
        $this->middlewares = array_merge($this->middlewares, $middlewares);
    }

    public function middlewares(array $middlewares = []): self
    {
        $this->addMiddlewares($middlewares);
        return $this;
    }
    
    public function get(string $path, mixed $handler, array $middlewares = []): void
    {
        $this->addRoute('GET', $path, $handler, $middlewares);
    }

    public function post(string $path, mixed $handler, array $middlewares = []): void
    {
        $this->addRoute('POST', $path, $handler, $middlewares);
    }

    public function put(string $path, mixed $handler, array $middlewares = []): void
    {
        $this->addRoute('PUT', $path, $handler, $middlewares);
    }

    public function delete(string $path, mixed $handler, array $middlewares = []): void
    {
        $this->addRoute('DELETE', $path, $handler, $middlewares);
    }

    public function group(string $prefix, Closure $callback, array $middlewares = []): void
    {
        $this->addMiddlewares($middlewares);
        $this->prefix = $this->prefix . $prefix;
        $callback($this);
    }

    public function getRoutes(): array
    {
        return $this->routes;
    }

    public function run(): void
    {
        $dispatcher = new RouteDispatcher($this->routes);
        $dispatcher->dispatch(new Request(), new Response());
    }
}