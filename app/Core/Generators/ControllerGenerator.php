<?php

namespace App\Core\Generators;

use App\Core\CodeGenerator;

/**
 * Generates Controller classes with CRUD operations
 */
class ControllerGenerator extends CodeGenerator
{
    public function generate(array $schema): array
    {
        $files = [];
        
        foreach ($schema['entities'] ?? [] as $entity) {
            $className = $this->toPascalCase($entity['name']) . 'Controller';
            $modelClass = $this->toPascalCase($entity['name']);
            $viewPrefix = $this->toSnakeCase($entity['name']);
            
            $template = $this->getControllerTemplate();
            $content = $this->renderTemplate($template, [
                'className' => $className,
                'modelClass' => $modelClass,
                'viewPrefix' => $viewPrefix,
            ]);
            
            $filename = "app/Http/Controllers/{$className}.php";
            $this->writeFile($filename, $content);
            $files[] = $filename;
        }
        
        return $files;
    }
    
    private function getControllerTemplate(): string
    {
        return <<<'PHP'
<?php

namespace App\Http\Controllers;

use App\Core\Request;
use App\Core\Response;
use Database\Models\{{modelClass}};

class {{className}}
{
    /**
     * Display a listing of the resource
     */
    public function index(Request $request): Response
    {
        $model = {{modelClass}}::make();
        $items = $model->all();
        
        return view('admin.{{viewPrefix}}.index', [
            'items' => $items,
            'title' => '{{modelClass}} Management'
        ]);
    }
    
    /**
     * Show the form for creating a new resource
     */
    public function create(Request $request): Response
    {
        return view('admin.{{viewPrefix}}.create', [
            'title' => 'Create {{modelClass}}'
        ]);
    }
    
    /**
     * Store a newly created resource
     */
    public function store(Request $request): Response
    {
        $model = {{modelClass}}::make();
        $data = $request->all();
        
        $id = $model->create($data);
        
        return json([
            'success' => true,
            'id' => $id,
            'message' => '{{modelClass}} created successfully'
        ], 201);
    }
    
    /**
     * Display the specified resource
     */
    public function show(Request $request): Response
    {
        $id = (int) $request->route('id');
        $model = {{modelClass}}::make();
        $item = $model->find($id);
        
        if (!$item) {
            return json(['error' => '{{modelClass}} not found'], 404);
        }
        
        return json(['data' => $item]);
    }
    
    /**
     * Show the form for editing the specified resource
     */
    public function edit(Request $request): Response
    {
        $id = (int) $request->route('id');
        $model = {{modelClass}}::make();
        $item = $model->find($id);
        
        if (!$item) {
            return response('{{modelClass}} not found', 404);
        }
        
        return view('admin.{{viewPrefix}}.edit', [
            'item' => $item,
            'title' => 'Edit {{modelClass}}'
        ]);
    }
    
    /**
     * Update the specified resource
     */
    public function update(Request $request): Response
    {
        $id = (int) $request->route('id');
        $model = {{modelClass}}::make();
        $data = $request->all();
        
        $success = $model->update($id, $data);
        
        return json([
            'success' => $success,
            'message' => $success ? '{{modelClass}} updated successfully' : 'Update failed'
        ]);
    }
    
    /**
     * Remove the specified resource
     */
    public function destroy(Request $request): Response
    {
        $id = (int) $request->route('id');
        $model = {{modelClass}}::make();
        
        $success = $model->delete($id);
        
        return json([
            'success' => $success,
            'message' => $success ? '{{modelClass}} deleted successfully' : 'Delete failed'
        ]);
    }
}
PHP;
    }
}
