<?php

namespace App\Core\Generators;

use App\Core\CodeGenerator;

/**
 * Generates database migration files from schema
 */
class MigrationGenerator extends CodeGenerator
{
    public function generate(array $schema): array
    {
        $files = [];
        $timestamp = date('Y_m_d_His');
        
        foreach ($schema['entities'] ?? [] as $entity) {
            $tableName = $entity['table'];
            $className = 'Create' . $this->toPascalCase($tableName) . 'Table';
            $fields = $this->generateFields($entity['fields'] ?? []);
            
            $template = $this->getMigrationTemplate();
            $content = $this->renderTemplate($template, [
                'className' => $className,
                'tableName' => $tableName,
                'fields' => $fields,
            ]);
            
            $filename = "database/migrations/{$timestamp}_{$tableName}.php";
            $this->writeFile($filename, $content);
            $files[] = $filename;
        }
        
        return $files;
    }
    
    private function generateFields(array $fields): string
    {
        $lines = [];
        
        foreach ($fields as $field) {
            $name = $field['name'];
            $type = strtoupper($field['type'] ?? 'TEXT');
            
            // Map common types to SQLite
            if ($type === 'STRING' || $type === 'VARCHAR') $type = 'TEXT';
            if ($type === 'INT' || $type === 'BIGINT') $type = 'INTEGER';
            
            $line = "                {$name} {$type}";
            
            if (!($field['nullable'] ?? false)) {
                $line .= " NOT NULL";
            }
            
            if (isset($field['default'])) {
                $default = is_string($field['default']) ? "'{$field['default']}'" : $field['default'];
                $line .= " DEFAULT {$default}";
            }
            
            if ($field['unique'] ?? false) {
                $line .= " UNIQUE";
            }
            
            $lines[] = $line;
        }
        
        return implode(",\n", $lines);
    }
    
    private function getMigrationTemplate(): string
    {
        return <<<'PHP'
<?php

use App\Core\Database;

return new class {
    public function up(Database $db): void
    {
        $db->query("
            CREATE TABLE IF NOT EXISTS {{tableName}} (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
{{fields}},
                created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
                updated_at DATETIME DEFAULT CURRENT_TIMESTAMP
            )
        ");
    }
    
    public function down(Database $db): void
    {
        $db->query("DROP TABLE IF EXISTS {{tableName}}");
    }
};
PHP;
    }
}
