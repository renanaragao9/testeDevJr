<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;

class DashboardController extends Controller
{
    public function index()
    {
        $products   = Product::with('images', 'category')->get();
        $categories = Category::with('images')->get();

        $productStatistics = $this->calculateProductStatistics($products);
        
        return response()->json([
            'products'   => $products,
            'categories' => $categories,
            'statistics' => $productStatistics,
        ]);
    }

    private function calculateProductStatistics($products)
    {
        // Agrupar produtos por category_id e calcular valores
        $categories = $products->groupBy('category_id')->map(function ($productsInCategory) {
            $categoryName = $productsInCategory->first()->category->name;

            // Calcular quantidade e valor total
            $totalQuantity = $productsInCategory->sum('qtd');
            $totalValue = $productsInCategory->sum(function ($product) {
                return $product->price * $product->qtd;
            });

            return [
                'category_name' => $categoryName,
                'products' => $productsInCategory,
                'product_count' => $productsInCategory->count(),
                'total_quantity' => $totalQuantity,
                'total_value' => $totalValue,
            ];
        });

        // Calcular estatísticas gerais
        $totalProducts = $products->count();
        $totalQuantityAll = $products->sum('qtd');
        $mostItemsProduct = $products->sortByDesc('qtd')->first();
        $mostExpensiveProduct = $products->sortByDesc('price')->first();
        $cheapestProduct = $products->sortBy('price')->first();

        return [
            'categories' => $categories,
            'totalProducts' => $totalProducts,
            'totalQuantityAll' => $totalQuantityAll,
            'mostItemsProduct' => $mostItemsProduct,
            'mostExpensiveProduct' => $mostExpensiveProduct,
            'cheapestProduct' => $cheapestProduct,
        ];
    }
}
