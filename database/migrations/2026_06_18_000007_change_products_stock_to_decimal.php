<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('products')) {
            DB::statement('ALTER TABLE products MODIFY stock DECIMAL(10,2) NOT NULL DEFAULT 0');
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('products')) {
            DB::statement('ALTER TABLE products MODIFY stock INT NOT NULL DEFAULT 0');
        }
    }
};
