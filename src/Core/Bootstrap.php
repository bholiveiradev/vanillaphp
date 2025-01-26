<?php

declare(strict_types=1);

namespace App\Core;

use App\Core\Http\{Request, Response};
use App\Http\Middlewares\Contracts\MiddlewareInterface;
use Exception;

class Bootstrap
{
    public static function dispatch(array $routes, Request $request, Response $response): mixed
    {
        foreach ($routes as $route) {
            if (self::isMatchingRoute($request, $route)) {
                $params = self::extractParamsFromUri($request, $route);
                $middlewares = array_reverse($route['middlewares']);

                $next = self::runHandler($route, $request, $response, $params);

                foreach ($middlewares as $middleware) {
                    $middleware = self::instantiateMiddleware($middleware);

                    $next = function () use ($middleware, $request, $response, $next) {
                        return $middleware->handle($request, $response, $next);
                    };
                }

                return $next();
            }
        }

        throw new Exception('Route not found', Response::HTTP_NOT_FOUND);
    }

    private static function instantiateMiddleware(string $middleware): MiddlewareInterface
    {
        $middlewareObject = new $middleware();

        if (!$middlewareObject instanceof MiddlewareInterface) {
            throw new Exception("[{$middleware}] must implement MiddlewareInterface", Response::HTTP_NOT_IMPLEMENTED);
        }

        return $middlewareObject;
    }

    private static function isMatchingRoute(Request $request, array $route): bool
    {
        return $request->getMethod() === $route['method'] &&
            preg_match(self::replacePathToRegex($route['uri']), $request->getUri());
    }

    private static function extractParamsFromUri(Request $request, array $route): array
    {
        $pathRegex = self::replacePathToRegex($route['uri']);

        preg_match($pathRegex, $request->getUri(), $matches);
        array_shift($matches);

        $request->setParams($matches);

        return $matches;
    }

    private static function runHandler(array $route, Request $request, Response $response, array $params): \Closure
    {
        return function () use ($route, $request, $response, $params) {
            $handler = $route['handler'];

            if ($handler instanceof \Closure || is_callable($handler)) {
                return $handler($request, $response, $params);
            }

            if (is_array($handler)) {
                [$controller, $method] = $handler;
                return call_user_func([new $controller(), $method], $request, $response, $params);
            }

            if (class_exists($handler)) {
                return call_user_func([new $handler(), 'handle'], $request, $response, $params);
            }

            throw new Exception('Not Implemented', Response::HTTP_NOT_IMPLEMENTED);
        };
    }

    private static function replacePathToRegex(string $path): string
    {
        $replace = preg_replace('#\{([\w]+)\}#', '(?P<$1>[^/]+)', $path);
        return "#^{$replace}$#";
    }
}
