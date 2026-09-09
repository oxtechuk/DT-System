<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->nullable()->constrained('product_categories')->nullOnDelete();
            $table->string('name');
            $table->string('sku', 50)->nullable()->unique();
            $table->string('barcode', 100)->nullable()->unique();
            $table->string('unit')->default('piece');       // piece | pack | kg | litre
            $table->decimal('selling_price', 10, 2);
            $table->decimal('purchase_price', 10, 2)->nullable();
            $table->unsignedSmallInteger('minimum_stock')->nullable();
            $table->boolean('track_inventory')->default(true);
            $table->boolean('active')->default(true);
            $table->timestamps();

            $table->index(['active', 'category_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
