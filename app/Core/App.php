<?php

namespace App\Core;

use Closure;

class App
{
    protected array $bindings = [];
    protected array $instances = [];
    protected static ?self $instance = null;

    public function __construct()
    {
        self::$instance = $this;
    }

    public static function getInstance(): self
    {
        if (self::$instance === null) {
            throw new \RuntimeException('L\'application n\'a pas été initialisée.');
        }

        return self::$instance;
    }

    public function bind(string $abstract, Closure|string $resolver): void
    {
        $this->bindings[$abstract] = $resolver;
    }

    public function singleton(string $abstract, Closure|string $resolver): void
    {
        $this->instances[$abstract] = null;
        $this->bindings[$abstract] = $resolver;
    }

    /**
     * @param string $abstract
     * @return mixed
     */
    public function make(string $abstract): mixed
    {
        if (array_key_exists($abstract, $this->instances) && $this->instances[$abstract] !== null) {
            return $this->instances[$abstract];
        }

        if (!isset($this->bindings[$abstract])) {
            if (class_exists($abstract)) {
                return $this->instances[$abstract] = new $abstract();
            }

            throw new \RuntimeException(sprintf('Service [%s] non enregistré.', $abstract));
        }

        $resolver = $this->bindings[$abstract];
        $instance = is_string($resolver) && class_exists($resolver)
            ? new $resolver()
            : $resolver($this);

        if (array_key_exists($abstract, $this->instances)) {
            $this->instances[$abstract] = $instance;
        }

        return $instance;
    }
}
