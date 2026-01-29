<?php

namespace App\Http\Controllers;

use App\Core\Request;
use App\Core\Response;
use Database\Models\Playlist;

class PlaylistController
{
    /**
     * Display a listing of the resource
     */
    public function index(Request $request): Response
    {
        $model = Playlist::make();
        $items = $model->all();
        
        return view('admin.playlist.index', [
            'items' => $items,
            'title' => 'Playlist Management'
        ]);
    }
    
    /**
     * Show the form for creating a new resource
     */
    public function create(Request $request): Response
    {
        return view('admin.playlist.create', [
            'title' => 'Create Playlist'
        ]);
    }
    
    /**
     * Store a newly created resource
     */
    public function store(Request $request): Response
    {
        $model = Playlist::make();
        $data = $request->all();
        
        $id = $model->create($data);
        
        return json([
            'success' => true,
            'id' => $id,
            'message' => 'Playlist created successfully'
        ], 201);
    }
    
    /**
     * Display the specified resource
     */
    public function show(Request $request): Response
    {
        $id = (int) $request->route('id');
        $model = Playlist::make();
        $item = $model->find($id);
        
        if (!$item) {
            return json(['error' => 'Playlist not found'], 404);
        }
        
        return json(['data' => $item]);
    }
    
    /**
     * Show the form for editing the specified resource
     */
    public function edit(Request $request): Response
    {
        $id = (int) $request->route('id');
        $model = Playlist::make();
        $item = $model->find($id);
        
        if (!$item) {
            return response('Playlist not found', 404);
        }
        
        return view('admin.playlist.edit', [
            'item' => $item,
            'title' => 'Edit Playlist'
        ]);
    }
    
    /**
     * Update the specified resource
     */
    public function update(Request $request): Response
    {
        $id = (int) $request->route('id');
        $model = Playlist::make();
        $data = $request->all();
        
        $success = $model->update($id, $data);
        
        return json([
            'success' => $success,
            'message' => $success ? 'Playlist updated successfully' : 'Update failed'
        ]);
    }
    
    /**
     * Remove the specified resource
     */
    public function destroy(Request $request): Response
    {
        $id = (int) $request->route('id');
        $model = Playlist::make();
        
        $success = $model->delete($id);
        
        return json([
            'success' => $success,
            'message' => $success ? 'Playlist deleted successfully' : 'Delete failed'
        ]);
    }
}