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

use Database\Models\Model;

class {{className}} extends Model
{
    protected string $table = '{{tableName}}';
    protected array $fillable = [{{fillable}}];
}
PHP;
    }
}
