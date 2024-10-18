<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search'); 
        $categories = Category::with('images')
            ->when($search, function ($query, $search) {
                return $query->where('name', 'like', '%' . $search . '%');
            })
            ->get();

        return response()->json($categories);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $category = Category::create([
            'name' => $request->name,
        ]);

        if ($request->hasFile('image')) {
    
            $imageController = new ImageController();
            $imageRequest = $request->merge([
                'imageable_id' => $category->id,
                'imageable_type' => Category::class,
            ]);

            $imageResponse = $imageController->store($imageRequest);

            $category->image = $imageResponse->getData();

        }

        return response()->json($category, 201);
    }
    
    public function show($id)
    {
        $category = Category::findOrFail($id);
        
        return response()->json($category);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $category = Category::findOrFail($id);
        
        $category->update($request->all());
        
        return response()->json($category);
    }

    public function destroy($id)
    {
        $category = Category::findOrFail($id);
        
        $category->delete();
        
        return response()->json(null, 204);
    }
}
