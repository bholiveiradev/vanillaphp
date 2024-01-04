<?php

declare(strict_types=1);

namespace App\Core;

use Closure;
use Exception;
use ReflectionClass;

class DIContainer
{
    private static array $bindings = [];

    public static function bind(string $abstract, $concrete): void
    {
        self::$bindings[$abstract] = $concrete;
    }

    public static function make(string $abstract)
    {
        if (isset(self::$bindings[$abstract])) {
            $concrete = self::$bindings[$abstract];

            if (is_string($concrete)) {
                return self::resolveConcrete($concrete);
            }

            if ($concrete instanceof Closure) {
                return $concrete();
            }

            throw new Exception("Binding para '{$abstract}' não é válido.");
        }

        throw new Exception("Classe ou interface '{$abstract}' não registrada no container.");
    }

    private static function resolveConcrete(string $concrete)
    {
        $reflector = new ReflectionClass($concrete);
        $constructor = $reflector->getConstructor();

        if (!$constructor) {
            return new $concrete;
        }

        $parameters = $constructor->getParameters();
        $dependencies = self::getDependencies($parameters);

        return $reflector->newInstanceArgs($dependencies);
    }

    private static function getDependencies(array $parameters)
    {
        $dependencies = [];

        foreach ($parameters as $parameter) {
            $dependency = $parameter->getClass();
            $dependencies[] = $dependency ? self::make($dependency->name) : null;
        }

        return $dependencies;
    }

    public static function bindRoutes(array $routes): void
    {
        foreach ($routes as $route) {
            if (is_array($route['handler'])) {
                [$controller] = $route['handler'];
                self::bind($controller, $controller);
            }
        }
    }
}
