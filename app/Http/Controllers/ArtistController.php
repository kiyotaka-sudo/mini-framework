<?php

namespace App\Http\Controllers;

use App\Core\Request;
use App\Core\Response;
use Database\Models\Artist;

class ArtistController
{
    /**
     * Display a listing of the resource
     */
    public function index(Request $request): Response
    {
        $model = Artist::make();
        $items = $model->all();
        
        return view('admin.artist.index', [
            'items' => $items,
            'title' => 'Artist Management'
        ]);
    }
    
    /**
     * Show the form for creating a new resource
     */
    public function create(Request $request): Response
    {
        return view('admin.artist.create', [
            'title' => 'Create Artist'
        ]);
    }
    
    /**
     * Store a newly created resource
     */
    public function store(Request $request): Response
    {
        $model = Artist::make();
        $data = $request->all();
        
        $id = $model->create($data);
        
        return json([
            'success' => true,
            'id' => $id,
            'message' => 'Artist created successfully'
        ], 201);
    }
    
    /**
     * Display the specified resource
     */
    public function show(Request $request): Response
    {
        $id = (int) $request->route('id');
        $model = Artist::make();
        $item = $model->find($id);
        
        if (!$item) {
            return json(['error' => 'Artist not found'], 404);
        }
        
        return json(['data' => $item]);
    }
    
    /**
     * Show the form for editing the specified resource
     */
    public function edit(Request $request): Response
    {
        $id = (int) $request->route('id');
        $model = Artist::make();
        $item = $model->find($id);
        
        if (!$item) {
            return response('Artist not found', 404);
        }
        
        return view('admin.artist.edit', [
            'item' => $item,
            'title' => 'Edit Artist'
        ]);
    }
    
    /**
     * Update the specified resource
     */
    public function update(Request $request): Response
    {
        $id = (int) $request->route('id');
        $model = Artist::make();
        $data = $request->all();
        
        $success = $model->update($id, $data);
        
        return json([
            'success' => $success,
            'message' => $success ? 'Artist updated successfully' : 'Update failed'
        ]);
    }
    
    /**
     * Remove the specified resource
     */
    public function destroy(Request $request): Response
    {
        $id = (int) $request->route('id');
        $model = Artist::make();
        
        $success = $model->delete($id);
        
        return json([
            'success' => $success,
            'message' => $success ? 'Artist deleted successfully' : 'Delete failed'
        ]);
    }
}