<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->string('payment_number', 30)->unique();
            $table->foreignId('order_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('customer_id')->nullable()->constrained()->nullOnDelete();
            $table->string('type')->default('income');  // income | refund
            $table->string('method');                   // cash | instapay | wallet (PaymentMethod enum)
            $table->decimal('amount', 10, 2);
            $table->string('reference')->nullable();    // InstaPay ref, wallet tx ID, etc.
            $table->timestamp('paid_at');
            $table->foreignId('received_by')->constrained('users')->restrictOnDelete();
            $table->text('notes')->nullable();
            $table->timestamps();

            // Critical indexes for shift reconciliation by method
            $table->index(['method', 'paid_at']);
            $table->index(['order_id']);
            $table->index('paid_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
