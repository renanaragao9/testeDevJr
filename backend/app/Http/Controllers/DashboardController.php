<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;

class DashboardController extends Controller
{
    public function index()
    {

        $products = Product::all();
        $categories = Category::all();
        
        return response()->json([
            'products' => $products,
            'categories' => $categories,
        ]);
    }
}
