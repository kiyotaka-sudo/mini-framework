<?php

namespace App\Core\Generators;

use App\Core\CodeGenerator;

/**
 * Generates Model classes from schema
 */
class ModelGenerator extends CodeGenerator
{
    public function generate(array $schema): array
    {
        $files = [];
        
        foreach ($schema['entities'] ?? [] as $entity) {
            $className = $this->toPascalCase($entity['name']);
            $tableName = $entity['table'];
            $fillable = $this->generateFillable($entity['fields'] ?? []);
            
            $template = $this->getModelTemplate();
            $content = $this->renderTemplate($template, [
                'className' => $className,
                'tableName' => $tableName,
                'fillable' => $fillable,
            ]);
            
            $filename = "database/models/{$className}.php";
            $this->writeFile($filename, $content);
            $files[] = $filename;
        }
        
        return $files;
    }
    
    private function generateFillable(array $fields): string
    {
        $names = array_column($fields, 'name');
        $quoted = array_map(fn($n) => "'{$n}'", $names);
        return implode(', ', $quoted);
    }
    
    private function getModelTemplate(): string
    {
        return <<<'PHP'
<?php

namespace Database\Models;

use App\Core\Database;
use PDO;

class {{className}}
{
    protected static string $table = '{{tableName}}';
    protected array $fillable = [{{fillable}}];
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
PHP;
    }
}
