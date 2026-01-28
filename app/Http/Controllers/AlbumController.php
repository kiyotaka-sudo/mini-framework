<?php

namespace App\Http\Controllers;

use App\Core\Request;
use App\Core\Response;
use Database\Models\Album;

class AlbumController
{
    /**
     * Display a listing of the resource
     */
    public function index(Request $request): Response
    {
        $model = Album::make();
        $items = $model->all();
        
        return view('admin.album.index', [
            'items' => $items,
            'title' => 'Album Management'
        ]);
    }
    
    /**
     * Show the form for creating a new resource
     */
    public function create(Request $request): Response
    {
        return view('admin.album.create', [
            'title' => 'Create Album'
        ]);
    }
    
    /**
     * Store a newly created resource
     */
    public function store(Request $request): Response
    {
        $model = Album::make();
        $data = $request->all();
        
        $id = $model->create($data);
        
        return json([
            'success' => true,
            'id' => $id,
            'message' => 'Album created successfully'
        ], 201);
    }
    
    /**
     * Display the specified resource
     */
    public function show(Request $request): Response
    {
        $id = (int) $request->route('id');
        $model = Album::make();
        $item = $model->find($id);
        
        if (!$item) {
            return json(['error' => 'Album not found'], 404);
        }
        
        return json(['data' => $item]);
    }
    
    /**
     * Show the form for editing the specified resource
     */
    public function edit(Request $request): Response
    {
        $id = (int) $request->route('id');
        $model = Album::make();
        $item = $model->find($id);
        
        if (!$item) {
            return response('Album not found', 404);
        }
        
        return view('admin.album.edit', [
            'item' => $item,
            'title' => 'Edit Album'
        ]);
    }
    
    /**
     * Update the specified resource
     */
    public function update(Request $request): Response
    {
        $id = (int) $request->route('id');
        $model = Album::make();
        $data = $request->all();
        
        $success = $model->update($id, $data);
        
        return json([
            'success' => $success,
            'message' => $success ? 'Album updated successfully' : 'Update failed'
        ]);
    }
    
    /**
     * Remove the specified resource
     */
    public function destroy(Request $request): Response
    {
        $id = (int) $request->route('id');
        $model = Album::make();
        
        $success = $model->delete($id);
        
        return json([
            'success' => $success,
            'message' => $success ? 'Album deleted successfully' : 'Delete failed'
        ]);
    }
}