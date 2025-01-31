<?php

namespace App\Core;

class Container
{
    private array $bindings = [];
    private array $instances = [];

    public function bind(string $abstract, string $concrete): void
    {
        $this->bindings[$abstract] = $concrete;
    }

    public function singleton(string $abstract, string $concrete): void
    {
        $this->instances[$abstract] = $this->resolve($concrete);
    }

    public function resolve(string $class)
    {
        if (isset($this->instances[$class])) {
            return $this->instances[$class];
        }

        if (isset($this->bindings[$class])) {
            return $this->resolve($this->bindings[$class]);
        }

        return $this->build($class);
    }

    private function build(string $class)
    {
        if (! class_exists($class)) {
            throw new \Exception("Class [{$class}] does not exist.");
        }

        $reflector = new \ReflectionClass($class);

        if (! $reflector->isInstantiable()) {
            throw new \Exception("Class [{$class}] is not instantiable.");
        }

        $constructor = $reflector->getConstructor();

        if (! $constructor) {
            return new $class;
        }

        $dependencies = array_map(
            function (\ReflectionParameter $param) {
                $type = $param->getType();
                $typeName = $type instanceof \ReflectionNamedType ? $type->getName() : null;
        
                if (!$typeName || in_array($typeName, ['int', 'float', 'string', 'bool', 'array'], true)) {
                    return null;
                }
        
                return $this->resolve($typeName);
            },
            $constructor->getParameters()
        );

        $dependencies = array_filter($dependencies, fn($dep) => $dep !== null);

        return $reflector->newInstanceArgs($dependencies);
    }
}
