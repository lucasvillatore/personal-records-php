<?php

namespace App\Application;

use Closure;
use ReflectionClass;
use ReflectionNamedType;
use RuntimeException;

class Container
{
    protected array $bindings = [];
    protected array $instances = [];

    public function bind(string $abstract, Closure $concrete): void
    {
        $this->bindings[$abstract] = $concrete;
    }

    public function singleton(string $abstract, Closure $concrete): void
    {
        $this->bindings[$abstract] = $concrete;
        $this->instances[$abstract] = null;
    }

    public function get(string $id): mixed
    {
        if (array_key_exists($id, $this->instances)) {
            if ($this->instances[$id] === null) {
                $this->instances[$id] = $this->resolve($id);
            }
            return $this->instances[$id];
        }

        if (isset($this->bindings[$id])) {
            return $this->bindings[$id]($this);
        }

        return $this->resolve($id);
    }

    protected function resolve(string $class): mixed
    {
        if (!class_exists($class) && !interface_exists($class)) {
            throw new RuntimeException("Classe ou interface [$class] não encontrada.");
        }

        if (interface_exists($class)) {
            if (!isset($this->bindings[$class])) {
                throw new RuntimeException("Nenhuma implementação registrada para a interface [$class].");
            }

            return $this->get($class);
        }

        $reflection = new ReflectionClass($class);

        if (!$reflection->isInstantiable()) {
            throw new RuntimeException("Classe [$class] não é instanciável.");
        }

        $constructor = $reflection->getConstructor();

        if (!$constructor) {
            return new $class;
        }

        $parameters = $constructor->getParameters();
        $dependencies = [];

        foreach ($parameters as $param) {
            $type = $param->getType();

            if (!$type instanceof ReflectionNamedType || $type->isBuiltin()) {
                throw new RuntimeException("Não é possível resolver parâmetro '{$param->getName()}' da classe [$class]");
            }

            $dependencies[] = $this->get($type->getName());
        }

        return $reflection->newInstanceArgs($dependencies);
    }
}
