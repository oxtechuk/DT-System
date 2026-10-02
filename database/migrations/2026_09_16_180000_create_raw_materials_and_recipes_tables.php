<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Raw materials & ingredients inventory (الخامات والمواد الأولية)
        Schema::create('raw_materials', function (Blueprint $table) {
            $table->id();
            $table->string('name');                      // e.g. بن إسبريسو، حليب طبيعي، سكر، أكواب 8oz
            $table->string('code')->nullable()->unique();
            $table->string('unit')->default('gram');      // gram, ml, piece, pack, kg, liter
            $table->decimal('current_stock', 10, 2)->default(0); // Available stock
            $table->decimal('unit_cost', 10, 4)->default(0);     // Cost per unit (e.g. 0.40 EGP per gram)
            $table->decimal('minimum_stock', 10, 2)->default(100); // Low stock alert threshold
            $table->boolean('active')->default(true);
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        // Product Ingredients / Recipe BOM (مكونات وخامات كل منتج)
        Schema::create('product_ingredients', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained('products')->cascadeOnDelete();
            $table->foreignId('raw_material_id')->constrained('raw_materials')->cascadeOnDelete();
            $table->decimal('quantity', 10, 2);          // Quantity required per 1 product serving
            $table->timestamps();

            $table->unique(['product_id', 'raw_material_id']);
        });

        // Ensure products table has purchase_price / cost column
        if (!Schema::hasColumn('products', 'purchase_price')) {
            Schema::table('products', function (Blueprint $table) {
                $table->decimal('purchase_price', 10, 2)->nullable()->default(0)->after('selling_price');
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('product_ingredients');
        Schema::dropIfExists('raw_materials');
    }
};
