<?php

declare(strict_types=1);

namespace App\Core\Http;

use Closure;

class Router
{
    private static array $routes = [];
    private static array $middlewares = [];
    private static ?string $prefix = null;

    public function __construct()
    {
        $this->setCorsHeaders();
    }

    private function setCorsHeaders(): void
    {
        header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Credentials: true");
        header('Access-Control-Allow-Methods: GET, PUT, PATCH, POST, DELETE');
        header('Access-Control-Max-Age: 1000');
        header('Access-Control-Allow-Headers: Origin, Cache-Control, Accept, Content-Type, X-Auth-Token , Authorization');
    }

    public static function group(string $prefix, Closure $callback, array $middlewares = []): void
    {
        $previousPrefix = self::$prefix;

        self::addMiddlewares($middlewares);
        self::$prefix = self::$prefix . $prefix;

        $callback(self::class);

        self::$prefix = $previousPrefix;
    }

    public static function middlewares(array $middlewares = []): self
    {
        self::addMiddlewares($middlewares);
        return new static;
    }

    public static function get(string $path, mixed $handler, array $middlewares = []): void
    {
        self::addRoute('GET', $path, $handler, $middlewares);
    }

    public static function post(string $path, mixed $handler, array $middlewares = []): void
    {
        self::addRoute('POST', $path, $handler, $middlewares);
    }

    public static function put(string $path, mixed $handler, array $middlewares = []): void
    {
        self::addRoute('PUT', $path, $handler, $middlewares);
    }

    public static function patch(string $path, mixed $handler, array $middlewares = []): void
    {
        self::addRoute('PATCH', $path, $handler, $middlewares);
    }

    public static function delete(string $path, mixed $handler, array $middlewares = []): void
    {
        self::addRoute('DELETE', $path, $handler, $middlewares);
    }

    public static function routes(): array
    {
        return self::$routes;
    }

    private static function addRoute(string $method, string $uri, mixed $handler, array $middlewares): void
    {
        self::$routes[] = [
            'method' => $method,
            'uri' => self::$prefix . rtrim($uri, '/') . '/',
            'handler' => $handler,
            'middlewares' => array_merge(self::$middlewares, $middlewares),
        ];
    }

    private static function addMiddlewares(array $middlewares): void
    {
        self::$middlewares = array_merge(self::$middlewares, $middlewares);
    }
}
