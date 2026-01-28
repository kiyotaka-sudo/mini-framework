<?php

namespace App\Http\Controllers;

use App\Core\Request;
use App\Core\Response;
use Database\Models\Produit;

class ProduitController
{
    /**
     * Display a listing of the resource
     */
    public function index(Request $request): Response
    {
        $model = Produit::make();
        $items = $model->all();
        
        return view('admin.produit.index', [
            'items' => $items,
            'title' => 'Produit Management'
        ]);
    }
    
    /**
     * Show the form for creating a new resource
     */
    public function create(Request $request): Response
    {
        return view('admin.produit.create', [
            'title' => 'Create Produit'
        ]);
    }
    
    /**
     * Store a newly created resource
     */
    public function store(Request $request): Response
    {
        $model = Produit::make();
        $data = $request->all();
        
        $id = $model->create($data);
        
        return json([
            'success' => true,
            'id' => $id,
            'message' => 'Produit created successfully'
        ], 201);
    }
    
    /**
     * Display the specified resource
     */
    public function show(Request $request): Response
    {
        $id = (int) $request->route('id');
        $model = Produit::make();
        $item = $model->find($id);
        
        if (!$item) {
            return json(['error' => 'Produit not found'], 404);
        }
        
        return json(['data' => $item]);
    }
    
    /**
     * Show the form for editing the specified resource
     */
    public function edit(Request $request): Response
    {
        $id = (int) $request->route('id');
        $model = Produit::make();
        $item = $model->find($id);
        
        if (!$item) {
            return response('Produit not found', 404);
        }
        
        return view('admin.produit.edit', [
            'item' => $item,
            'title' => 'Edit Produit'
        ]);
    }
    
    /**
     * Update the specified resource
     */
    public function update(Request $request): Response
    {
        $id = (int) $request->route('id');
        $model = Produit::make();
        $data = $request->all();
        
        $success = $model->update($id, $data);
        
        return json([
            'success' => $success,
            'message' => $success ? 'Produit updated successfully' : 'Update failed'
        ]);
    }
    
    /**
     * Remove the specified resource
     */
    public function destroy(Request $request): Response
    {
        $id = (int) $request->route('id');
        $model = Produit::make();
        
        $success = $model->delete($id);
        
        return json([
            'success' => $success,
            'message' => $success ? 'Produit deleted successfully' : 'Delete failed'
        ]);
    }
}