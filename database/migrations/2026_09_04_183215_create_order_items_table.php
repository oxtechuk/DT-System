<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->cascadeOnDelete();
            $table->foreignId('product_id')->nullable()->constrained()->nullOnDelete();
            $table->string('item_type');       // session | product | other  (OrderItemType enum)
            $table->string('name');            // snapshot of product name at time of sale
            $table->unsignedSmallInteger('quantity')->default(1);
            $table->decimal('unit_price', 10, 2); // snapshot of price — never changes with product
            $table->decimal('total', 10, 2);
            $table->json('metadata')->nullable();  // extra context: pricing_rule_id, duration_minutes, etc.
            $table->timestamps();

            $table->index('order_id');
            $table->index('item_type');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('order_items');
    }
};
