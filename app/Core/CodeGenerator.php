<?php

namespace App\Core;

/**
 * Base class for code generators
 * Provides common templating and file writing utilities
 */
abstract class CodeGenerator
{
    protected string $outputPath;
    
    public function __construct(string $outputPath = '')
    {
        $this->outputPath = $outputPath ?: dirname(__DIR__, 2);
    }
    
    /**
     * Generate code based on schema
     */
    abstract public function generate(array $schema): array;
    
    /**
     * Replace placeholders in template
     */
    protected function renderTemplate(string $template, array $data): string
    {
        foreach ($data as $key => $value) {
            $placeholder = '{{' . $key . '}}';
            $template = str_replace($placeholder, $value, $template);
        }
        
        return $template;
    }
    
    /**
     * Write content to file
     */
    protected function writeFile(string $path, string $content): bool
    {
        $fullPath = $this->outputPath . '/' . $path;
        $directory = dirname($fullPath);
        
        if (!is_dir($directory)) {
            mkdir($directory, 0755, true);
        }
        
        return file_put_contents($fullPath, $content) !== false;
    }
    
    /**
     * Convert snake_case to PascalCase
     */
    protected function toPascalCase(string $string): string
    {
        return str_replace(' ', '', ucwords(str_replace('_', ' ', $string)));
    }
    
    /**
     * Convert PascalCase to snake_case
     */
    protected function toSnakeCase(string $string): string
    {
        return strtolower(preg_replace('/(?<!^)[A-Z]/', '_$0', $string));
    }
    
    /**
     * Pluralize a word (simple English rules)
     */
    protected function pluralize(string $word): string
    {
        if (substr($word, -1) === 'y') {
            return substr($word, 0, -1) . 'ies';
        }
        if (in_array(substr($word, -1), ['s', 'x', 'z']) || in_array(substr($word, -2), ['ch', 'sh'])) {
            return $word . 'es';
        }
        return $word . 's';
    }
}
