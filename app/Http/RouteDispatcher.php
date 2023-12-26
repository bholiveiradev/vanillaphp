<?php

declare(strict_types=1);

namespace App\Http;

use App\Middlewares\Contracts\MiddlewareInterface;
use Exception;

class RouteDispatcher
{
    public function __construct(private array $routes)
    {
    }

    public function dispatch(Request $request, Response $response): mixed
    {
        foreach ($this->routes as $route) {
            if ($this->isMatchingRoute($request, $route)) {
                $params = $this->extractParamsFromUri($request, $route);
                $middlewares = array_reverse($route['middlewares']);

                $next = $this->runHandler($route, $request, $response, $params);

                foreach ($middlewares as $middleware) {
                    $middlewareObject = $this->instantiateMiddleware($middleware);

                    $next = function () use ($middlewareObject, $request, $response, $next) {
                        return $middlewareObject->handle($request, $response, $next);
                    };
                }

                return $next();
            }
        }

        throw new Exception('Route not found', 404);
    }

    private function isMatchingRoute(Request $request, array $route): bool
    {
        return $request->getMethod() === $route['method'] &&
            preg_match($this->replacePathToRegex($route['uri']), $request->getUri());
    }

    private function extractParamsFromUri(Request $request, array $route): array
    {
        $pathRegex = $this->replacePathToRegex($route['uri']);
        
        preg_match($pathRegex, $request->getUri(), $matches);
        array_shift($matches);

        $request->setParams($matches);

        return $matches;
    }

    private function runHandler(array $route, Request $request, Response $response, array $params): \Closure
    {
        return function () use ($route, $request, $response, $params) {
            $handler = $route['handler'];

            if ($handler instanceof \Closure) {
                return $handler($request, $response);
            }

            if (is_array($handler)) {
                list($controller, $method) = $handler;
                return (new $controller)->$method($request, $response);
            }

            if (class_exists($handler)) {
                return call_user_func(new $handler($params));
            }

            throw new Exception('Not Implemented', 501);
        };
    }

    private function instantiateMiddleware(string $middleware): MiddlewareInterface
    {
        $middlewareObject = new $middleware;

        if (!$middlewareObject instanceof MiddlewareInterface) {
            throw new Exception("{$middleware} must implement MiddlewareInterface");
        }

        return $middlewareObject;
    }

    private function replacePathToRegex(string $path): string
    {
        $regex = preg_replace('#\{([\w]+)\}#', '(?P<$1>[^/]+)', $path);
        return '#^' . $regex . '$#';
    }
}