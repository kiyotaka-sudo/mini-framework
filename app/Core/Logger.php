<?php

namespace App\Core;

class Logger
{
    protected string $path;

    public function __construct(string $path)
    {
        $this->path = $path;
        $dir = dirname($this->path);
        if (!is_dir($dir)) {
            mkdir($dir, 0777, true);
        }
    }

    public function info(string $message, array $context = []): void
    {
        $this->write('info', $message, $context);
    }

    public function error(string $message, array $context = []): void
    {
        $this->write('error', $message, $context);
    }

    public function debug(string $message, array $context = []): void
    {
        $this->write('debug', $message, $context);
    }

    protected function write(string $level, string $message, array $context = []): void
    {
        $timestamp = (new \DateTimeImmutable('now'))->format('Y-m-d H:i:s');
        $entry = sprintf(
            "[%s] %s: %s %s\n",
            $timestamp,
            strtoupper($level),
            $message,
            $context ? json_encode($context, JSON_UNESCAPED_UNICODE) : ''
        );
        file_put_contents($this->path, $entry, FILE_APPEND | LOCK_EX);
    }
}
