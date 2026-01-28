<?php

namespace App\Http\Controllers;

use App\Core\Request;
use App\Core\Response;
use App\Core\Generators\PageViewGenerator;

class PageBuilderController
{
    /**
     * Show the page builder interface
     */
    public function index(Request $request): Response
    {
        // Use false for layout to render without the app wrapper
        return view('page_builder.index', [
            'title' => 'Concepteur de Pages Visuel'
        ], false);
    }


    /**
     * Save the designed page
     */
    public function save(Request $request): Response
    {
        $data = json_decode($request->getBody(), true);
        
        if (!$data || !isset($data['name']) || !isset($data['layout'])) {
            return json(['error' => 'Données invalides'], 400);
        }

        $route = $data['route'] ?? '/view-page/' . strtolower(str_replace(' ', '-', $data['name']));
        
        $generator = new PageViewGenerator();
        $generated = $generator->generate([
            'page_name' => $data['name'],
            'layout' => $data['layout']
        ]);
        $fileName = basename($generated[0]);
        
        // Register the custom route
        $this->registerRoute($route, $fileName);

        return json([
            'success' => true,
            'message' => "Page '{$data['name']}' générée avec succès.",
            'file' => $fileName,
            'url' => $route
        ]);
    }
    
    /**
     * Register a custom route for the generated page
     */
    private function registerRoute(string $route, string $viewFile): void
    {
        $routesFile = dirname(__DIR__, 3) . '/routes/web.php';
        $content = file_get_contents($routesFile);
        
        $viewName = str_replace('.php', '', $viewFile);
        
        // Check if this route already exists
        if (str_contains($content, "get('$route',")) {
            return; // Route already exists
        }
        
        // Find the position to insert the new route (before the closing of the function)
        $routeCode = "\n    // Page générée: $viewName\n" .
                     "    \$router->get('$route', function() {\n" .
                     "        return view('pages.$viewName', ['title' => '$viewName']);\n" .
                     "    }, ['middleware' => ['log']]);\n";
        
        // Insert before the final }; of the routes file
        $insertPos = strrpos($content, '};');
        if ($insertPos !== false) {
            $newContent = substr($content, 0, $insertPos) . $routeCode . substr($content, $insertPos);
            file_put_contents($routesFile, $newContent);
        }
    }


    /**
     * Preview a block or layout
     */
    public function preview(Request $request): Response
    {
        // For preview, we can just return the rendered HTML
        $data = json_decode($request->getBody(), true);
        $generator = new PageViewGenerator();
        
        // This is a simplified preview (normally would use a temp file)
        // For now, let's just use reflection to call renderTemplate if we want
        // But better is to just send the rendered content.
        return json(['html' => 'Rendered content would go here']);
    }
}
