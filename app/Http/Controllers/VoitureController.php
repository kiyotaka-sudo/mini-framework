<?php

namespace App\Http\Controllers;

use App\Core\Request;
use App\Core\Response;
use Database\Models\Voiture;

class VoitureController
{
    /**
     * Display a listing of the resource
     */
    public function index(Request $request): Response
    {
        $model = Voiture::make();
        $items = $model->all();
        
        return view('admin.voiture.index', [
            'items' => $items,
            'title' => 'Voiture Management'
        ]);
    }
    
    /**
     * Show the form for creating a new resource
     */
    public function create(Request $request): Response
    {
        return view('admin.voiture.create', [
            'title' => 'Create Voiture'
        ]);
    }
    
    /**
     * Store a newly created resource
     */
    public function store(Request $request): Response
    {
        $model = Voiture::make();
        $data = $request->all();
        
        $id = $model->create($data);
        
        return json([
            'success' => true,
            'id' => $id,
            'message' => 'Voiture created successfully'
        ], 201);
    }
    
    /**
     * Display the specified resource
     */
    public function show(Request $request): Response
    {
        $id = (int) $request->route('id');
        $model = Voiture::make();
        $item = $model->find($id);
        
        if (!$item) {
            return json(['error' => 'Voiture not found'], 404);
        }
        
        return json(['data' => $item]);
    }
    
    /**
     * Show the form for editing the specified resource
     */
    public function edit(Request $request): Response
    {
        $id = (int) $request->route('id');
        $model = Voiture::make();
        $item = $model->find($id);
        
        if (!$item) {
            return response('Voiture not found', 404);
        }
        
        return view('admin.voiture.edit', [
            'item' => $item,
            'title' => 'Edit Voiture'
        ]);
    }
    
    /**
     * Update the specified resource
     */
    public function update(Request $request): Response
    {
        $id = (int) $request->route('id');
        $model = Voiture::make();
        $data = $request->all();
        
        $success = $model->update($id, $data);
        
        return json([
            'success' => $success,
            'message' => $success ? 'Voiture updated successfully' : 'Update failed'
        ]);
    }
    
    /**
     * Remove the specified resource
     */
    public function destroy(Request $request): Response
    {
        $id = (int) $request->route('id');
        $model = Voiture::make();
        
        $success = $model->delete($id);
        
        return json([
            'success' => $success,
            'message' => $success ? 'Voiture deleted successfully' : 'Delete failed'
        ]);
    }
}