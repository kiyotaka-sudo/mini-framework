<?php

namespace App\Core;

class App
{
    protected static ?App $instance = null;
    protected array $services = [];

    public function __construct()
    {
        self::$instance = $this;
    }

    public static function getInstance(): static
    {
        if (!self::$instance) {
            self::$instance = new static();
        }
        return self::$instance;
    }

    public function bind(string $key, callable $resolver): void
    {
        $this->services[$key] = $resolver;
    }

    public function make(string $key): mixed
    {
        if (isset($this->services[$key])) {
            return call_user_func($this->services[$key], $this);
        }
        throw new \Exception("Service not found: {$key}");
    }
}
