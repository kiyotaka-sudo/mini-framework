<?php

namespace App\Core\Generators;

use App\Core\CodeGenerator;

/**
 * Generates routes and appends them to routes/web.php
 */
class RouteGenerator extends CodeGenerator
{
    public function generate(array $schema): array
    {
        $routes = [];
        
        foreach ($schema['entities'] ?? [] as $entity) {
            $entityName = $this->toPascalCase($entity['name']);
            $viewPrefix = $this->toSnakeCase($entity['name']);
            $controllerClass = $entityName . 'Controller';
            
            $routes[] = $this->generateEntityRoutes($viewPrefix, $controllerClass);
        }
        
        $this->appendToRoutesFile($routes);
        
        return ['routes/web.php'];
    }
    
    private function generateEntityRoutes(string $viewPrefix, string $controllerClass): string
    {
        return <<<PHP

    // ============================================
    // {$controllerClass} Routes
    // ============================================
    
    // Admin pages
    \$router->get('/admin/{$viewPrefix}', {$controllerClass}::class . '@index');
    \$router->get('/admin/{$viewPrefix}/create', {$controllerClass}::class . '@create');
    \$router->get('/admin/{$viewPrefix}/{id}/edit', {$controllerClass}::class . '@edit');
    
    // API endpoints
    \$router->get('/api/{$viewPrefix}', {$controllerClass}::class . '@index');
    \$router->post('/api/{$viewPrefix}', {$controllerClass}::class . '@store');
    \$router->get('/api/{$viewPrefix}/{id}', {$controllerClass}::class . '@show');
    \$router->put('/api/{$viewPrefix}/{id}', {$controllerClass}::class . '@update');
    \$router->delete('/api/{$viewPrefix}/{id}', {$controllerClass}::class . '@destroy');
PHP;
    }
    
    private function appendToRoutesFile(array $routes): void
    {
        $routesFile = $this->outputPath . '/routes/web.php';
        $content = file_get_contents($routesFile);
        
        // Find the closing brace of the return function
        $insertPosition = strrpos($content, '};');
        
        if ($insertPosition !== false) {
            $beforeClose = substr($content, 0, $insertPosition);
            $afterClose = substr($content, $insertPosition);
            
            $newContent = $beforeClose . implode("\n", $routes) . "\n" . $afterClose;
            file_put_contents($routesFile, $newContent);
        }
    }
}
