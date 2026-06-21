<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::latest()->paginate(20);
        return view('admin.products.index', compact('products'));
    }

    public function create()
    {
        return view('admin.products.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:191',
            'emoji' => 'nullable|string|max:5',
            'description' => 'nullable|string',
            'price_per_unit' => 'required|numeric|min:0',
            'unit_type' => 'required|in:unit,kilo',
            'stock' => 'required|numeric|min:0',
        ]);

        // Set old 'price' field for backward compatibility
        $data['price'] = $data['price_per_unit'];
        
        // Set default emoji if not provided
        if (empty($data['emoji'])) {
            $data['emoji'] = '🐟';
        }

        Product::create($data);

        return redirect()->route('admin.products.index')->with('status', 'Product added');
    }

    public function edit(Product $product)
    {
        return view('admin.products.edit', compact('product'));
    }

    public function update(Request $request, Product $product)
    {
        $data = $request->validate([
            'name' => 'required|string|max:191',
            'emoji' => 'nullable|string|max:5',
            'description' => 'nullable|string',
            'price_per_unit' => 'required|numeric|min:0',
            'unit_type' => 'required|in:unit,kilo',
            'stock' => 'required|numeric|min:0',
        ]);

        // Set old 'price' field for backward compatibility
        $data['price'] = $data['price_per_unit'];
        
        // Keep existing emoji if not provided
        if (empty($data['emoji'])) {
            $data['emoji'] = $product->emoji ?? '🐟';
        }

        $product->update($data);

        return redirect()->route('admin.products.index')->with('status', 'Product updated');
    }

    public function destroy(Product $product)
    {
        $product->delete();
        return back()->with('status', 'Product removed');
    }

    public function restock(Request $request, Product $product)
    {
        $data = $request->validate([
            'amount' => 'required|numeric|min:0.5',
        ]);

        $product->stock = $product->stock + $data['amount'];
        $product->save();

        return back()->with('status', "Added {$data['amount']} to {$product->name}");
    }

    public function restockAjax(Request $request, Product $product)
    {
        $data = $request->validate([
            'amount' => 'required|numeric|min:0.5',
        ]);

        $oldStock = $product->stock;
        $product->stock = $product->stock + $data['amount'];
        $product->save();

        return response()->json([
            'success' => true,
            'message' => "Restocked {$product->name}",
            'oldStock' => $oldStock,
            'newStock' => $product->stock,
            'product' => $product
        ]);
    }

    public function dashboard()
    {
        // Get all products for the table
        $allProducts = Product::latest()->get();
        
        // Separate by stock status
        $lowStockProducts = Product::where('stock', '<=', 10)->orderBy('stock')->get();
        $totalProducts = Product::count();
        $inStockProducts = Product::where('stock', '>', 10)->count();
        $lowStockCount = $lowStockProducts->count();
        $outOfStockCount = Product::where('stock', '<=', 0)->count();

        return view('admin.dashboard', compact(
            'allProducts',
            'lowStockProducts',
            'totalProducts',
            'inStockProducts',
            'lowStockCount',
            'outOfStockCount'
        ));
    }
}
