<?php

namespace Database\Models;

use App\Core\Database;

abstract class Model
{
    protected string $table = '';
    protected string $primaryKey = 'id';
    protected array $fillable = [];
    protected Database $database;

    public function __construct(Database $database)
    {
        $this->database = $database;
    }

    public static function make(): static
    {
        return new static(app()->make(Database::class));
    }

    public function all(): array
    {
        return $this->database->fetchAll(sprintf('SELECT * FROM %s', $this->getTable()));
    }

    public function find(mixed $id): ?array
    {
        $result = $this->database->fetch(
            sprintf('SELECT * FROM %s WHERE %s = :id LIMIT 1', $this->getTable(), $this->primaryKey),
            ['id' => $id]
        );

        return $result ?: null;
    }

    public function create(array $attributes): array
    {
        $data = $this->filter($attributes);
        if (empty($data)) {
            return [];
        }

        $columns = array_keys($data);
        $placeholders = implode(', ', array_map(fn ($column) => ':' . $column, $columns));
        $SQL = sprintf(
            'INSERT INTO %s (%s) VALUES (%s)',
            $this->getTable(),
            implode(', ', $columns),
            $placeholders
        );

        $this->database->execute($SQL, $data);
        return $this->find($this->database->getConnection()->lastInsertId());
    }

    public function update(mixed $id, array $attributes): int
    {
        $data = $this->filter($attributes);
        if (empty($data)) {
            return 0;
        }

        $assignments = implode(', ', array_map(fn ($column) => "{$column} = :{$column}", array_keys($data)));
        $sql = sprintf(
            'UPDATE %s SET %s WHERE %s = :id',
            $this->getTable(),
            $assignments,
            $this->primaryKey
        );

        $data['id'] = $id;
        return $this->database->execute($sql, $data);
    }

    public function delete(mixed $id): int
    {
        return $this->database->execute(
            sprintf('DELETE FROM %s WHERE %s = :id', $this->getTable(), $this->primaryKey),
            ['id' => $id]
        );
    }

    protected function filter(array $attributes): array
    {
        if (empty($this->fillable)) {
            return $attributes;
        }

        return array_intersect_key($attributes, array_flip($this->fillable));
    }

    protected function getTable(): string
    {
        if ($this->table !== '') {
            return $this->table;
        }

        $short = (new \ReflectionClass($this))->getShortName();
        return strtolower($short) . 's';
    }
}
