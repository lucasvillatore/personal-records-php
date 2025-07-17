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

    /**
     * Registra uma implementação para uma interface ou classe abstrata.
     * Pode ser uma closure factory ou o nome da classe concreta.
     */
    public function bind(string $abstract, string|Closure $concrete): void
    {
        $this->bindings[$abstract] = $concrete;
    }

    /**
     * Registra um singleton: instância única criada na primeira chamada.
     */
    public function singleton(string $abstract, Closure $concrete): void
    {
        $this->bindings[$abstract] = function ($container) use ($concrete, $abstract) {
            if (!isset($this->instances[$abstract])) {
                $this->instances[$abstract] = $concrete($container);
            }
            return $this->instances[$abstract];
        };
    }

    /**
     * Retorna a instância para o id solicitado.
     */
    public function get(string $id): mixed
    {
        if (isset($this->instances[$id])) {
            return $this->instances[$id];
        }

        if (isset($this->bindings[$id])) {
            $concrete = $this->bindings[$id];

            if ($concrete instanceof Closure) {
                $object = $concrete($this);
            } elseif (is_string($concrete)) {
                $object = $this->resolve($concrete);
            } else {
                throw new RuntimeException("Binding inválido para [$id]");
            }

            if (isset($this->instances[$id])) {
                return $this->instances[$id];
            }

            return $object;
        }

        return $this->resolve($id);
    }

    /**
     * Resolve uma classe pelo reflection e injeta dependências.
     */
    protected function resolve(string $class): mixed
    {
        if (!class_exists($class) && !interface_exists($class)) {
            throw new RuntimeException("Classe ou interface [$class] não encontrada.");
        }

        if (interface_exists($class) && !isset($this->bindings[$class])) {
            throw new RuntimeException("Nenhuma implementação registrada para a interface [$class].");
        }

        if (interface_exists($class) && isset($this->bindings[$class])) {
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
