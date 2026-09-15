<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Inventory Locations (Warehouse, Display)
        Schema::create('inventory_locations', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('code')->unique();    // warehouse, display
            $table->enum('type', ['warehouse', 'display', 'other'])->default('warehouse');
            $table->boolean('active')->default(true);
            $table->timestamps();
        });

        // Inventory Transactions Ledger
        Schema::create('inventory_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->restrictOnDelete();
            $table->foreignId('location_id')->constrained('inventory_locations')->restrictOnDelete();
            $table->enum('type', [
                'purchase',
                'sale',
                'display_transfer',
                'inventory_adjustment',
                'damage',
                'loss',
                'return',
            ]);
            $table->integer('quantity');          // positive = in, negative = out
            $table->string('reference_type')->nullable(); // App\Models\Order, Purchase, etc.
            $table->unsignedBigInteger('reference_id')->nullable();
            $table->text('notes')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->index(['product_id', 'location_id', 'type']);
        });

        // Inventory Counts (Opening daily / Weekly warehouse)
        Schema::create('inventory_counts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('location_id')->constrained('inventory_locations')->restrictOnDelete();
            $table->enum('type', ['daily_opening', 'weekly', 'manual'])->default('manual');
            $table->date('count_date');
            $table->enum('status', ['draft', 'submitted', 'approved'])->default('draft');
            $table->text('notes')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });

        // Inventory Count Items
        Schema::create('inventory_count_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('inventory_count_id')->constrained()->cascadeOnDelete();
            $table->foreignId('product_id')->constrained()->restrictOnDelete();
            $table->unsignedInteger('expected_quantity')->default(0);
            $table->unsignedInteger('actual_quantity')->default(0);
            $table->integer('difference')->default(0); // computed: actual - expected
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('inventory_count_items');
        Schema::dropIfExists('inventory_counts');
        Schema::dropIfExists('inventory_transactions');
        Schema::dropIfExists('inventory_locations');
    }
};
