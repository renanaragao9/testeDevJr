<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with('category', 'images')->get();
        
        return response()->json($products);
    }

    public function create()
    {
        $categories = Category::all();
        
        return response()->json(['categories' => $categories]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'        => 'required|string|max:255',
            'description' => 'nullable|string',
            'category_id' => 'required|exists:categories,id',
            'price'       => 'required|numeric|min:0',
            'qtd'         => 'required|integer|min:0',      
        ]);

        $product = Product::create($request->all());

        if ($request->hasFile('image')) {
            
            // chama o metodo do Controller
            $imageController = new ImageController();
            $imageRequest    = $request->merge([
                'imageable_id'   => $product->id,
                'imageable_type' => Product::class,
            ]);

            $imageResponse  = $imageController->store($imageRequest);
            $product->image = $imageResponse->getData();
        }

        return response()->json($product, 201);
    }


    public function show($id)
    {
        $product = Product::with('category')->findOrFail($id);
        
        return response()->json($product);
    }

    public function edit($id)
    {
        $product = Product::with('category')->findOrFail($id);
        
        $categories = Category::all();
        
        return response()->json(['product' => $product, 'categories' => $categories]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name'          => 'required|string|max:255',
            'description'   => 'nullable|string',
            'category_id'   => 'required|exists:categories,id',
        ]);

        $product = Product::findOrFail($id);
        
        $product->update($request->all());

        if ($request->hasFile('image')) {
            
            $imageController = new ImageController();
            $imageRequest    = $request->merge([
                'imageable_id'   => $product->id,
                'imageable_type' => Product::class,
            ]);

            $imageResponse  = $imageController->store($imageRequest);
            $product->image = $imageResponse->getData();
            
            $product->save();
        }

        return response()->json($product);
    }

    public function destroy($id)
    {
        
        $product = Product::findOrFail($id);
        
        $product->delete();
        
        return response()->json(null, 204);
    }
}
