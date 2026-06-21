<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Product;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::updateOrCreate([
            'email' => 'admin@example.com',
        ], [
            'name' => 'SeaFresh Admin',
            'password' => 'password',
            'is_admin' => true,
        ]);

        User::updateOrCreate([
            'email' => 'buyer@example.com',
        ], [
            'name' => 'Hotel Buyer',
            'password' => 'password',
            'is_admin' => false,
        ]);

        // Seed products with initial stock and weight-based pricing
        $products = [
            ['name' => 'Samaki', 'emoji' => '🐟', 'description' => 'Premium whole fish for hotels and restaurants.', 'price' => 45000, 'stock' => 100, 'unit_type' => 'kilo', 'price_per_unit' => 10000],
            ['name' => 'Pweza', 'emoji' => '🐙', 'description' => 'Fresh octopus, cleaned and ready for your kitchen.', 'price' => 52000, 'stock' => 50, 'unit_type' => 'kilo', 'price_per_unit' => 20000],
            ['name' => 'Ngisi', 'emoji' => '🐟', 'description' => 'Locally sourced seafood with authentic Zanzibar flavour.', 'price' => 38000, 'stock' => 150, 'unit_type' => 'kilo', 'price_per_unit' => 15000],
            ['name' => 'Kaa', 'emoji' => '🦀', 'description' => 'Zanzibar crab for stews, soups, and grills.', 'price' => 47000, 'stock' => 20, 'unit_type' => 'unit', 'price_per_unit' => 47000],
            ['name' => 'Kamba', 'emoji' => '🦞', 'description' => 'Sweet lobster sourced from the Indian Ocean.', 'price' => 90000, 'stock' => 15, 'unit_type' => 'unit', 'price_per_unit' => 50000],
        ];

        foreach ($products as $p) {
            Product::updateOrCreate(['name' => $p['name']], $p);
        }
    }
}
