<?php

namespace Database\Models;

use App\Core\Database;
use PDO;

class Voiture
{
    protected static string $table = 'voitures';
    protected array $fillable = ['marques', 'caution'];
    protected array $attributes = [];
    
    public static function make(): self
    {
        return new self();
    }
    
    public function all(): array
    {
        $db = app()->make(Database::class);
        $stmt = $db->query("SELECT * FROM " . static::$table);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function find(int $id): ?array
    {
        $db = app()->make(Database::class);
        $stmt = $db->prepare("SELECT * FROM " . static::$table . " WHERE id = ?");
        $stmt->execute([$id]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ?: null;
    }
    
    public function create(array $data): ?int
    {
        $db = app()->make(Database::class);
        $filtered = array_intersect_key($data, array_flip($this->fillable));
        $columns = implode(', ', array_keys($filtered));
        $placeholders = implode(', ', array_fill(0, count($filtered), '?'));
        
        $sql = "INSERT INTO " . static::$table . " ({$columns}) VALUES ({$placeholders})";
        $stmt = $db->prepare($sql);
        $stmt->execute(array_values($filtered));
        
        return (int) $db->lastInsertId();
    }
    
    public function update(int $id, array $data): bool
    {
        $db = app()->make(Database::class);
        $filtered = array_intersect_key($data, array_flip($this->fillable));
        $sets = implode(', ', array_map(fn($k) => "{$k} = ?", array_keys($filtered)));
        
        $sql = "UPDATE " . static::$table . " SET {$sets} WHERE id = ?";
        $stmt = $db->prepare($sql);
        return $stmt->execute([...array_values($filtered), $id]);
    }
    
    public function delete(int $id): bool
    {
        $db = app()->make(Database::class);
        $stmt = $db->prepare("DELETE FROM " . static::$table . " WHERE id = ?");
        return $stmt->execute([$id]);
    }
}