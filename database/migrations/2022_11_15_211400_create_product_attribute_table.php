<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('product_attribute_value', function (Blueprint $table) {
            $table->foreignId('product_id')->constrained('products');
            $table->foreignId('attribute_id')->constrained('attributes');
            $table->foreignId('attribute_value_id')->constrained('attribute_values');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product_attribute');
    }
};
