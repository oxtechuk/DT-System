<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->string('full_name');
            $table->string('phone', 30)->unique();
            $table->string('email')->nullable()->unique();
            $table->string('customer_type')->default('registered'); // registered | guest
            $table->string('source')->nullable();                   // walk_in | mobile | referral
            $table->string('status')->default('active');            // active | inactive | blocked
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index('phone');
            $table->index('status');
            $table->index('customer_type');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('customers');
    }
};
