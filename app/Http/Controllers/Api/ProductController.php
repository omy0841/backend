<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;

class ProductController extends Controller
{
    /**
     * Get all products as JSON (for frontend consumption)
     */
    public function index()
    {
        $products = Product::select('id', 'name', 'emoji', 'description', 'unit_type', 'price_per_unit', 'stock')
            ->orderBy('name')
            ->get();

        return response()->json([
            'status' => 'success',
            'data' => $products,
        ]);
    }

    /**
     * Get a single product by ID
     */
    public function show($id)
    {
        $product = Product::select('id', 'name', 'emoji', 'description', 'unit_type', 'price_per_unit', 'stock')
            ->findOrFail($id);

        return response()->json([
            'status' => 'success',
            'data' => $product,
        ]);
    }
}
