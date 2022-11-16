<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('stock_product_attribute_value', function (Blueprint $table) {
            $table->foreignId('stock_id')->constrained('stock')->cascadeOnDelete();
            $table->foreignId('product_attribute_value_id')->constrained('product_attribute_value')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product_stock_attribute');
    }
};
