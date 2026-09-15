<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('shifts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->restrictOnDelete();
            $table->dateTime('opened_at');
            $table->dateTime('closed_at')->nullable();

            // Cash reconciliation
            $table->decimal('opening_cash', 10, 2)->default(0);  // cash at start of shift
            $table->decimal('expected_cash', 10, 2)->nullable(); // computed: opening + cash_sales - cash_expenses
            $table->decimal('actual_cash', 10, 2)->nullable();   // entered by cashier at closing
            $table->decimal('cash_difference', 10, 2)->nullable(); // actual - expected

            // Totals by payment method (computed at close)
            $table->decimal('total_cash', 10, 2)->default(0);
            $table->decimal('total_instapay', 10, 2)->default(0);
            $table->decimal('total_wallet', 10, 2)->default(0);
            $table->decimal('total_expenses', 10, 2)->default(0);
            $table->decimal('total_revenue', 10, 2)->default(0);

            $table->enum('status', ['open', 'closed'])->default('open');
            $table->text('closing_notes')->nullable();
            $table->timestamps();

            $table->index(['user_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('shifts');
    }
};
