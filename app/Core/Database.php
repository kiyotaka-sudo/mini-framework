<?php

namespace App\Core;

use PDO;
use PDOException;
use PDOStatement;
use Throwable;

class Database
{
    protected PDO $connection;
    protected string $driver;

    public function exec(string $sql): int
    {
        return $this->connection->exec($sql);
    }

    public function __construct(PDO $connection, string $driver)
    {
        $this->connection = $connection;
        $this->driver = $driver;
        $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $this->connection->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    }

    public static function connectFromEnv(): self
    {
        $driver = strtolower($_ENV['DB_CONNECTION'] ?? 'sqlite');
        $options = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ];

        $connection = null;

        if ($driver === 'mysql') {
            $host = $_ENV['DB_HOST'] ?? '127.0.0.1';
            $port = $_ENV['DB_PORT'] ?? '3306';
            $database = $_ENV['DB_DATABASE'] ?? 'mini_framework';
            $charset = $_ENV['DB_CHARSET'] ?? 'utf8mb4';
            $username = $_ENV['DB_USERNAME'] ?? 'root';
            $password = $_ENV['DB_PASSWORD'] ?? '';

            $dsn = sprintf('mysql:host=%s;port=%s;dbname=%s;charset=%s', $host, $port, $database, $charset);
            try {
                $connection = new PDO($dsn, $username, $password, $options);
            } catch (PDOException $exception) {
                logger()->error('Connexion MySQL impossible, bascule sur sqlite', ['error' => $exception->getMessage()]);
                $driver = 'sqlite';
            }
        }

        if ($driver !== 'mysql') {
            $path = $_ENV['DB_DATABASE'] ?? dirname(__DIR__, 2) . '/storage/database.sqlite';
            if (!file_exists($path)) {
                touch($path);
            }

            $dsn = sprintf('sqlite:%s', $path);
            $connection = new PDO($dsn, null, null, $options);
        }

        return new self($connection, $driver);
    }

    public function getConnection(): PDO
    {
        return $this->connection;
    }

    public function getDriver(): string
    {
        return $this->driver;
    }

    public function fetchAll(string $sql, array $bindings = []): array
    {
        return $this->raw($sql, $bindings)->fetchAll();
    }

    public function fetch(string $sql, array $bindings = []): array|false
    {
        return $this->raw($sql, $bindings)->fetch();
    }

    public function execute(string $sql, array $bindings = []): int
    {
        return $this->raw($sql, $bindings)->rowCount();
    }

    public function transaction(callable $callback): mixed
    {
        $this->connection->beginTransaction();
        try {
            $result = $callback($this);
            $this->connection->commit();
            return $result;
        } catch (Throwable $exception) {
            $this->connection->rollBack();
            throw $exception;
        }
    }

    public function query(string $sql): \PDOStatement
    {
        return $this->connection->query($sql);
    }

    public function prepare(string $sql): \PDOStatement
    {
        return $this->connection->prepare($sql);
    }

    public function lastInsertId(): string
    {
        return $this->connection->lastInsertId();
    }

    protected function raw(string $sql, array $bindings = []): PDOStatement
    {
        $statement = $this->connection->prepare($sql);
        $statement->execute($bindings);
        return $statement;
    }
}
