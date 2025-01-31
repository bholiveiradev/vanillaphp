<?php

declare(strict_types=1);

namespace App\Core;

use App\Core\Http\{Request, Response};
use App\Http\Middlewares\Contracts\MiddlewareInterface;
use Exception;

class Bootstrap
{
    private Container $container;

    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    public function dispatch(array $routes, Request $request, Response $response): mixed
    {
        foreach ($routes as $route) {
            if ($this->isMatchingRoute($request, $route)) {
                $params = $this->extractParamsFromUri($request, $route);
                $middlewares = array_reverse($route['middlewares']);

                $next = function () use ($route, $request, $response, $params) {
                    return $this->runHandler($route, $request, $response, $params);
                };

                foreach ($middlewares as $middleware) {
                    $middlewareInstance = $this->instantiateMiddleware($middleware);
                    $next = fn() => $middlewareInstance->handle($request, $response, $next);
                }

                return $next();
            }
        }

        throw new Exception('Route not found', Response::HTTP_NOT_FOUND);
    }

    private function instantiateMiddleware(string $middleware): MiddlewareInterface
    {
        $middlewareInstance = new $middleware();

        if (!$middlewareInstance instanceof MiddlewareInterface) {
            throw new Exception("[{$middleware}] must implement MiddlewareInterface", Response::HTTP_NOT_IMPLEMENTED);
        }

        return $middlewareInstance;
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

    private function runHandler(array $route, Request $request, Response $response, array $params): mixed
    {
        $handler = $route['handler'];

        if ($handler instanceof \Closure) {
            return $handler($request, $response, $params);
        }

        if (is_array($handler)) {
            [$controller, $method] = $handler;
            $controllerInstance = $this->container->resolve($controller);

            return call_user_func([$controllerInstance, $method], $request, $response, $params);
        }

        if (class_exists($handler)) {
            return call_user_func([$this->container->resolve($handler), 'handle'], $request, $response, $params);
        }

        throw new Exception('Not Implemented', Response::HTTP_NOT_IMPLEMENTED);
    }

    private function replacePathToRegex(string $path): string
    {
        $replace = preg_replace('#\{([\w]+)\}#', '(?P<$1>[^/]+)', $path);
        return "#^{$replace}$#";
    }
}
