<?php

namespace App\Http\Controllers;

use App\Core\Request;
use App\Core\Response;
use Database\Models\Fifa;

class FifaController
{
    /**
     * Display a listing of the resource
     */
    public function index(Request $request): Response
    {
        $model = Fifa::make();
        $items = $model->all();
        
        return view('admin.fifa.index', [
            'items' => $items,
            'title' => 'Fifa Management'
        ]);
    }
    
    /**
     * Show the form for creating a new resource
     */
    public function create(Request $request): Response
    {
        return view('admin.fifa.create', [
            'title' => 'Create Fifa'
        ]);
    }
    
    /**
     * Store a newly created resource
     */
    public function store(Request $request): Response
    {
        $model = Fifa::make();
        $data = $request->all();
        
        $id = $model->create($data);
        
        return json([
            'success' => true,
            'id' => $id,
            'message' => 'Fifa created successfully'
        ], 201);
    }
    
    /**
     * Display the specified resource
     */
    public function show(Request $request): Response
    {
        $id = (int) $request->route('id');
        $model = Fifa::make();
        $item = $model->find($id);
        
        if (!$item) {
            return json(['error' => 'Fifa not found'], 404);
        }
        
        return json(['data' => $item]);
    }
    
    /**
     * Show the form for editing the specified resource
     */
    public function edit(Request $request): Response
    {
        $id = (int) $request->route('id');
        $model = Fifa::make();
        $item = $model->find($id);
        
        if (!$item) {
            return response('Fifa not found', 404);
        }
        
        return view('admin.fifa.edit', [
            'item' => $item,
            'title' => 'Edit Fifa'
        ]);
    }
    
    /**
     * Update the specified resource
     */
    public function update(Request $request): Response
    {
        $id = (int) $request->route('id');
        $model = Fifa::make();
        $data = $request->all();
        
        $success = $model->update($id, $data);
        
        return json([
            'success' => $success,
            'message' => $success ? 'Fifa updated successfully' : 'Update failed'
        ]);
    }
    
    /**
     * Remove the specified resource
     */
    public function destroy(Request $request): Response
    {
        $id = (int) $request->route('id');
        $model = Fifa::make();
        
        $success = $model->delete($id);
        
        return json([
            'success' => $success,
            'message' => $success ? 'Fifa deleted successfully' : 'Delete failed'
        ]);
    }
}