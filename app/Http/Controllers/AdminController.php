<?php

namespace App\Http\Controllers;

use App\Core\Request;
use App\Core\Response;
use App\Core\Database;
use PDO;

class AdminController
{
    /**
     * Central Admin Dashboard
     * Lists all manageable entities found in the system
     */
    public function index(Request $request): Response
    {
        $entities = $this->getGeneratedEntities();
        
        return view('admin.dashboard', [
            'entities' => $entities,
            'title' => 'Administration - Tableau de Bord'
        ]);
    }
    
    /**
     * Scans the controllers or models to find what we have generated
     */
    private function getGeneratedEntities(): array
    {
        $entities = [];
        $controllerPath = dirname(__DIR__, 2) . '/Http/Controllers';
        
        if (is_dir($controllerPath)) {
            $files = scandir($controllerPath);
            foreach ($files as $file) {
                if (str_ends_with($file, 'Controller.php') && 
                    !in_array($file, ['HomeController.php', 'AuthController.php', 'BuilderController.php', 'AdminController.php', 'BackupController.php', 'UserController.php', 'TaskController.php', 'PageBuilderController.php'])) {
                    
                    $name = str_replace('Controller.php', '', $file);
                    $slug = strtolower(preg_replace('/(?<!^)[A-Z]/', '_$0', $name));
                    
                    $entities[] = [
                        'name' => $name,
                        'slug' => $slug,
                        'url' => "/admin/{$slug}"
                    ];
                }
            }
        }
        
        return $entities;
    }
}
