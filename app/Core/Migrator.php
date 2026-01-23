<?php

namespace App\Core;

class Migrator
{
    public function __construct(protected Database $database, protected string $path)
    {
        if (!is_dir($this->path)) {
            mkdir($this->path, 0755, true);
        }
    }

    public function run(): array
    {
        $this->createMigrationsTable();
        $executed = $this->fetchExecuted();
        $ran = [];
        $batch = $this->nextBatchNumber();

        $files = glob($this->path . '/*.php') ?: [];
        sort($files);

        foreach ($files as $file) {
            $name = basename($file);
            if (in_array($name, $executed, true)) {
                continue;
            }

            $migration = require $file;
            if (is_callable($migration)) {
                $migration($this->database);
                $this->record($name, $batch);
                $ran[] = $name;
            }
        }

        return $ran;
    }

    protected function createMigrationsTable(): void
    {
        $this->database->execute(
            'CREATE TABLE IF NOT EXISTS migrations (
                migration VARCHAR(255) PRIMARY KEY,
                batch INTEGER NOT NULL,
                ran_at TEXT NOT NULL
            )'
        );
    }

    protected function fetchExecuted(): array
    {
        $rows = $this->database->fetchAll('SELECT migration FROM migrations');
        return array_column($rows, 'migration');
    }

    protected function nextBatchNumber(): int
    {
        $row = $this->database->fetch('SELECT MAX(batch) as batch FROM migrations');
        return (($row['batch'] ?? 0) + 1);
    }

    protected function record(string $name, int $batch): void
    {
        $this->database->execute(
            'INSERT INTO migrations (migration, batch, ran_at) VALUES (:migration, :batch, :ran_at)',
            [
                'migration' => $name,
                'batch' => $batch,
                'ran_at' => (new \DateTimeImmutable())->format('Y-m-d H:i:s'),
            ]
        );
    }
}
