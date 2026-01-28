<?php

namespace App\Http\Controllers;

use App\Core\Request;
use App\Core\Response;
use Database\Models\Client;

class ClientController
{
    /**
     * Display a listing of the resource
     */
    public function index(Request $request): Response
    {
        $model = Client::make();
        $items = $model->all();
        
        return view('admin.client.index', [
            'items' => $items,
            'title' => 'Client Management'
        ]);
    }
    
    /**
     * Show the form for creating a new resource
     */
    public function create(Request $request): Response
    {
        return view('admin.client.create', [
            'title' => 'Create Client'
        ]);
    }
    
    /**
     * Store a newly created resource
     */
    public function store(Request $request): Response
    {
        $model = Client::make();
        $data = $request->all();
        
        $id = $model->create($data);
        
        return json([
            'success' => true,
            'id' => $id,
            'message' => 'Client created successfully'
        ], 201);
    }
    
    /**
     * Display the specified resource
     */
    public function show(Request $request): Response
    {
        $id = (int) $request->route('id');
        $model = Client::make();
        $item = $model->find($id);
        
        if (!$item) {
            return json(['error' => 'Client not found'], 404);
        }
        
        return json(['data' => $item]);
    }
    
    /**
     * Show the form for editing the specified resource
     */
    public function edit(Request $request): Response
    {
        $id = (int) $request->route('id');
        $model = Client::make();
        $item = $model->find($id);
        
        if (!$item) {
            return response('Client not found', 404);
        }
        
        return view('admin.client.edit', [
            'item' => $item,
            'title' => 'Edit Client'
        ]);
    }
    
    /**
     * Update the specified resource
     */
    public function update(Request $request): Response
    {
        $id = (int) $request->route('id');
        $model = Client::make();
        $data = $request->all();
        
        $success = $model->update($id, $data);
        
        return json([
            'success' => $success,
            'message' => $success ? 'Client updated successfully' : 'Update failed'
        ]);
    }
    
    /**
     * Remove the specified resource
     */
    public function destroy(Request $request): Response
    {
        $id = (int) $request->route('id');
        $model = Client::make();
        
        $success = $model->delete($id);
        
        return json([
            'success' => $success,
            'message' => $success ? 'Client deleted successfully' : 'Delete failed'
        ]);
    }
}