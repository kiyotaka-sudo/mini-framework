<?php

namespace App\Http\Controllers;

use App\Core\Request;
use App\Core\Response;
use Database\Models\Test1;

class Test1Controller
{
    /**
     * Display a listing of the resource
     */
    public function index(Request $request): Response
    {
        $model = Test1::make();
        $items = $model->all();
        
        return view('admin.test1.index', [
            'items' => $items,
            'title' => 'Test1 Management'
        ]);
    }
    
    /**
     * Show the form for creating a new resource
     */
    public function create(Request $request): Response
    {
        return view('admin.test1.create', [
            'title' => 'Create Test1'
        ]);
    }
    
    /**
     * Store a newly created resource
     */
    public function store(Request $request): Response
    {
        $model = Test1::make();
        $data = $request->all();
        
        $id = $model->create($data);
        
        return json([
            'success' => true,
            'id' => $id,
            'message' => 'Test1 created successfully'
        ], 201);
    }
    
    /**
     * Display the specified resource
     */
    public function show(Request $request): Response
    {
        $id = (int) $request->route('id');
        $model = Test1::make();
        $item = $model->find($id);
        
        if (!$item) {
            return json(['error' => 'Test1 not found'], 404);
        }
        
        return json(['data' => $item]);
    }
    
    /**
     * Show the form for editing the specified resource
     */
    public function edit(Request $request): Response
    {
        $id = (int) $request->route('id');
        $model = Test1::make();
        $item = $model->find($id);
        
        if (!$item) {
            return response('Test1 not found', 404);
        }
        
        return view('admin.test1.edit', [
            'item' => $item,
            'title' => 'Edit Test1'
        ]);
    }
    
    /**
     * Update the specified resource
     */
    public function update(Request $request): Response
    {
        $id = (int) $request->route('id');
        $model = Test1::make();
        $data = $request->all();
        
        $success = $model->update($id, $data);
        
        return json([
            'success' => $success,
            'message' => $success ? 'Test1 updated successfully' : 'Update failed'
        ]);
    }
    
    /**
     * Remove the specified resource
     */
    public function destroy(Request $request): Response
    {
        $id = (int) $request->route('id');
        $model = Test1::make();
        
        $success = $model->delete($id);
        
        return json([
            'success' => $success,
            'message' => $success ? 'Test1 deleted successfully' : 'Delete failed'
        ]);
    }
}