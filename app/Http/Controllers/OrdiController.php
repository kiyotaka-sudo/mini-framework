<?php

namespace App\Http\Controllers;

use App\Core\Request;
use App\Core\Response;
use Database\Models\Ordi;

class OrdiController
{
    /**
     * Display a listing of the resource
     */
    public function index(Request $request): Response
    {
        $model = Ordi::make();
        $items = $model->all();
        
        return view('admin.ordi.index', [
            'items' => $items,
            'title' => 'Ordi Management'
        ]);
    }
    
    /**
     * Show the form for creating a new resource
     */
    public function create(Request $request): Response
    {
        return view('admin.ordi.create', [
            'title' => 'Create Ordi'
        ]);
    }
    
    /**
     * Store a newly created resource
     */
    public function store(Request $request): Response
    {
        $model = Ordi::make();
        $data = $request->all();
        
        $id = $model->create($data);
        
        return json([
            'success' => true,
            'id' => $id,
            'message' => 'Ordi created successfully'
        ], 201);
    }
    
    /**
     * Display the specified resource
     */
    public function show(Request $request): Response
    {
        $id = (int) $request->route('id');
        $model = Ordi::make();
        $item = $model->find($id);
        
        if (!$item) {
            return json(['error' => 'Ordi not found'], 404);
        }
        
        return json(['data' => $item]);
    }
    
    /**
     * Show the form for editing the specified resource
     */
    public function edit(Request $request): Response
    {
        $id = (int) $request->route('id');
        $model = Ordi::make();
        $item = $model->find($id);
        
        if (!$item) {
            return response('Ordi not found', 404);
        }
        
        return view('admin.ordi.edit', [
            'item' => $item,
            'title' => 'Edit Ordi'
        ]);
    }
    
    /**
     * Update the specified resource
     */
    public function update(Request $request): Response
    {
        $id = (int) $request->route('id');
        $model = Ordi::make();
        $data = $request->all();
        
        $success = $model->update($id, $data);
        
        return json([
            'success' => $success,
            'message' => $success ? 'Ordi updated successfully' : 'Update failed'
        ]);
    }
    
    /**
     * Remove the specified resource
     */
    public function destroy(Request $request): Response
    {
        $id = (int) $request->route('id');
        $model = Ordi::make();
        
        $success = $model->delete($id);
        
        return json([
            'success' => $success,
            'message' => $success ? 'Ordi deleted successfully' : 'Delete failed'
        ]);
    }
}