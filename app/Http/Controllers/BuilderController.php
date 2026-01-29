<?php

namespace App\Http\Controllers;

use App\Core\Request;
use App\Core\Response;
use App\Core\Generators\MigrationGenerator;
use App\Core\Generators\ModelGenerator;
use App\Core\Generators\ControllerGenerator;
use App\Core\Generators\ViewGenerator;
use App\Core\Generators\RouteGenerator;
use App\Core\Database;
use App\Core\Migrator;

class BuilderController

{
    /**
     * Display the project builder interface
     */
    public function index(Request $request): Response
    {
        return view('builder.index', [
            'title' => 'Visual Project Builder'
        ], null);
    }
    
    /**
     * Generate project files from schema
     */
    public function generate(Request $request): Response
    {
        try {
            $body = $request->getBody();
            logger()->log("Received body: " . $body);
            
            $schema = json_decode($body, true);
            
            if (json_last_error() !== JSON_ERROR_NONE) {
                return json(['error' => 'Invalid JSON: ' . json_last_error_msg()], 400);
            }
            
            if (!$this->validateSchema($schema)) {
                return json(['error' => 'Invalid schema structure'], 400);
            }
            
            $generated = [];
            
            // Generate migrations
            $migrationGen = new MigrationGenerator();
            $generated['migrations'] = $migrationGen->generate($schema);
            
            // Generate models
            $modelGen = new ModelGenerator();
            $generated['models'] = $modelGen->generate($schema);
            
            // Generate controllers
            $controllerGen = new ControllerGenerator();
            $generated['controllers'] = $controllerGen->generate($schema);
            
            // Generate views
            $viewGen = new ViewGenerator();
            $generated['views'] = $viewGen->generate($schema);
            
            // Generate routes
            $routeGen = new RouteGenerator();
            $generated['routes'] = $routeGen->generate($schema);
            
            // Execute Migrations automatically!
            $db = app()->make(Database::class);
            $migrator = new Migrator($db, dirname(__DIR__, 3) . '/database/migrations');
            $generated['ran_migrations'] = $migrator->run();
            
            // Add use statements to routes
            $this->addControllerImports($schema);

            
            return json([
                'success' => true,
                'message' => 'Project generated successfully!',
                'files' => $generated
            ]);
            
        } catch (\Exception $e) {
            logger()->log("Generation error: " . $e->getMessage());
            return json([
                'error' => 'Generation failed: ' . $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ], 500);
        }
    }
    
    /**
     * Preview generated code
     */
    public function preview(Request $request): Response
    {
        $schema = $this->getSchemaFromRequest($request);
        
        // Generate preview without writing files
        $modelGen = new ModelGenerator();
        $modelGen = new \ReflectionClass($modelGen);
        
        return json([
            'schema' => $schema,
            'preview' => 'Code preview would go here'
        ]);
    }
    
    private function getSchemaFromRequest(Request $request): array
    {
        $body = $request->getBody();
        return json_decode($body, true) ?? [];
    }
    
    private function validateSchema(array $schema): bool
    {
        if (empty($schema['entities'])) {
            return false;
        }
        
        foreach ($schema['entities'] as $entity) {
            if (empty($entity['name']) || empty($entity['table'])) {
                return false;
            }
        }
        
        return true;
    }
    
    private function addControllerImports(array $schema): void
    {
        $routesFile = dirname(__DIR__, 3) . '/routes/web.php';
        $content = file_get_contents($routesFile);
        
        $imports = [];
        foreach ($schema['entities'] ?? [] as $entity) {
            $className = ucwords(str_replace('_', '', ucwords($entity['name'], '_')));
            $controllerClass = $className . 'Controller';
            $import = "use App\\Http\\Controllers\\{$controllerClass};";
            
            if (strpos($content, $import) === false) {
                $imports[] = $import;
            }
        }
        
        if (!empty($imports)) {
            // Find the last use statement
            preg_match_all('/^use [^;]+;$/m', $content, $matches, PREG_OFFSET_CAPTURE);
            if (!empty($matches[0])) {
                $lastUse = end($matches[0]);
                $insertPos = $lastUse[1] + strlen($lastUse[0]);
                
                $before = substr($content, 0, $insertPos);
                $after = substr($content, $insertPos);
                
                $newContent = $before . "\n" . implode("\n", $imports) . $after;
                file_put_contents($routesFile, $newContent);
            }
        }
    }
}
