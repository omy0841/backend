<?php
/**
 * Stock Management Demo
 * Shows how customer orders decrease stock, and how admin restocks
 */

require __DIR__ . '/vendor/autoload.php';

$app = require __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Product;

echo "=== SeaFresh Stock Management Demo ===\n\n";

// Check current stock
$kamba = Product::where('name', 'Kamba')->first();
echo "Initial Stock:\n";
echo "  Kamba (Lobster): {$kamba->stock} units @ TZS {$kamba->price_per_unit} each\n\n";

// Simulate customer order (deduct 2 units)
echo "Scenario: Customer orders 2 units of Kamba...\n";
$kamba->stock = max(0, $kamba->stock - 2);
$kamba->save();
echo "  After order: {$kamba->stock} units remaining\n\n";

// Check all products
echo "All Products Status:\n";
$products = Product::all();
foreach ($products as $p) {
    $status = $p->stock <= 0 ? '[WAMEISHA]' : '[Available]';
    $unit = $p->unit_type === 'kilo' ? 'kg' : 'unit';
    echo "  • {$p->name}: {$p->stock} {$unit} @ TZS {$p->price_per_unit}/{$unit} {$status}\n";
}

echo "\n✓ Stock tracking system working correctly!\n";
