<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function create()
    {
        // Guard in case the products table does not exist yet (pre-migration)
        try {
            if (\Illuminate\Support\Facades\Schema::hasTable('products')) {
                $products = \App\Models\Product::orderBy('name')->get();
            } else {
                // fallback list so the order page still renders
                $fallback = [
                    (object)['id' => 1, 'name' => 'Samaki', 'description' => 'Premium whole fish', 'price' => 45000, 'stock' => 0],
                    (object)['id' => 2, 'name' => 'Pweza', 'description' => 'Fresh octopus', 'price' => 52000, 'stock' => 0],
                    (object)['id' => 3, 'name' => 'Ngisi', 'description' => 'Locally sourced seafood', 'price' => 38000, 'stock' => 0],
                ];
                $products = collect($fallback);
            }
        } catch (\Exception $e) {
            $products = collect([]);
        }

        return view('order.create', compact('products'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'contact_name' => 'required|string|max:191',
            'hotel_name' => 'required|string|max:191',
            'phone' => 'required|string|max:50',
            'email' => 'nullable|email|max:191',
            'delivery_location' => 'required|string|max:191',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'items' => 'nullable|array',
            'items.*' => 'string|max:191',
            'quantity' => 'nullable|array',
            'quantity.*' => 'nullable|numeric|min:0.5|max:100',
            'custom_items' => 'nullable|string|max:1000',
            'special_request' => 'nullable|string|max:1000',
        ]);

        $selectedItems = $data['items'] ?? [];
        $quantities = $data['quantity'] ?? [];
        $orderItems = [];
        $total = 0;

        foreach ($selectedItems as $productId) {
            $product = \App\Models\Product::find($productId);
            if (!$product) continue;
            $qty = max(0.5, floatval($quantities[$product->id] ?? 1));

            if ($product->unit_type === 'unit' && intval($qty) != $qty) {
                return back()->withErrors(['items' => "{$product->name} must be ordered in whole units."])->withInput();
            }

            if ($qty > $product->stock) {
                return back()->withErrors(['items' => "Insufficient stock for {$product->name}. Available: {$product->stock}"])->withInput();
            }

            $unitLabel = $product->unit_type === 'kilo' ? 'kg' : 'unit';
            $orderItems[] = "{$product->name} x {$qty} {$unitLabel}";
            $pricePerUnit = $product->price_per_unit ?? $product->price;
            $total += $pricePerUnit * $qty;

            $product->stock = max(0, $product->stock - $qty);
            $product->save();
        }

        $customItems = array_filter(array_map('trim', explode("\n", $data['custom_items'] ?? '')));
        foreach ($customItems as $customItem) {
            $orderItems[] = $customItem;
        }

        if (empty($orderItems)) {
            return back()
                ->withErrors(['items' => 'Please select at least one seafood product or add a custom item.'])
                ->withInput();
        }

        Order::create([
            'user_id' => auth()->id(),
            'contact_name' => $data['contact_name'],
            'hotel_name' => $data['hotel_name'],
            'phone' => $data['phone'],
            'email' => $data['email'] ?? null,
            'delivery_location' => $data['delivery_location'],
            'latitude' => $data['latitude'] ?? null,
            'longitude' => $data['longitude'] ?? null,
            'items' => $orderItems,
            'special_request' => $data['special_request'] ?? null,
            'total_amount' => $total,
            'status' => 'pending',
        ]);

        return redirect()->route('order.history')->with('status', 'Order received. Our admin will review and approve it soon.');
    }

    public function history()
    {
        $orders = auth()->user()->orders()->latest()->paginate(12);

        return view('order.history', compact('orders'));
    }
}
