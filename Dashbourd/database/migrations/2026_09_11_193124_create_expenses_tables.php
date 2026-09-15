<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Expense Categories
        Schema::create('expense_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('code')->unique();   // cleaning, maintenance, bills, supplies
            $table->boolean('active')->default(true);
            $table->timestamps();
        });

        // Expenses
        Schema::create('expenses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained('expense_categories')->restrictOnDelete();
            $table->decimal('amount', 10, 2);
            $table->enum('payment_method', ['cash', 'instapay', 'wallet', 'card', 'other'])->default('cash');
            $table->date('expense_date');
            $table->string('description');
            $table->string('reference')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['expense_date', 'payment_method']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('expenses');
        Schema::dropIfExists('expense_categories');
    }
};
