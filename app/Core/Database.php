<?php

namespace App\Core;

use PDO;
use PDOException;

class Database
{
    protected ?PDO $pdo = null;
    protected array $config;

    public function __construct(array $config = [])
    {
        $this->config = $config ?: $this->getDefaultConfig();
    }

    protected function getDefaultConfig(): array
    {
        return [
            'driver' => $_ENV['DB_CONNECTION'] ?? 'mysql',
            'host' => $_ENV['DB_HOST'] ?? '127.0.0.1',
            'port' => $_ENV['DB_PORT'] ?? '3306',
            'database' => $_ENV['DB_DATABASE'] ?? 'miniframework',
            'username' => $_ENV['DB_USERNAME'] ?? 'root',
            'password' => $_ENV['DB_PASSWORD'] ?? '',
            'charset' => 'utf8mb4',
            'options' => [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
            ]
        ];
    }

    public function connect(): PDO
    {
        if ($this->pdo !== null) {
            return $this->pdo;
        }

        try {
            $driver = $this->config['driver'];
            
            if ($driver === 'sqlite') {
                $dsn = "sqlite:" . $this->config['database'];
                $this->pdo = new PDO($dsn, null, null, $this->config['options']);
            } else {
                $dsn = sprintf(
                    "%s:host=%s;port=%s;dbname=%s;charset=%s",
                    $driver,
                    $this->config['host'],
                    $this->config['port'],
                    $this->config['database'],
                    $this->config['charset']
                );
                
                $this->pdo = new PDO(
                    $dsn,
                    $this->config['username'],
                    $this->config['password'],
                    $this->config['options']
                );
            }

            return $this->pdo;
        } catch (PDOException $e) {
            throw new \Exception("Database connection failed: " . $e->getMessage());
        }
    }

    public function query(string $sql, array $params = []): \PDOStatement
    {
        $pdo = $this->connect();
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt;
    }

    public function fetch(string $sql, array $params = []): ?array
    {
        return $this->query($sql, $params)->fetch() ?: null;
    }

    public function fetchAll(string $sql, array $params = []): array
    {
        return $this->query($sql, $params)->fetchAll();
    }

    public function execute(string $sql, array $params = []): bool
    {
        return $this->query($sql, $params)->rowCount() > 0;
    }

    public function lastInsertId(): string
    {
        return $this->connect()->lastInsertId();
    }

    public function beginTransaction(): bool
    {
        return $this->connect()->beginTransaction();
    }

    public function commit(): bool
    {
        return $this->connect()->commit();
    }

    public function rollback(): bool
    {
        return $this->connect()->rollBack();
    }

    public function getPdo(): PDO
    {
        return $this->connect();
    }
}
