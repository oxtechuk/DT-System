<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pricing_rules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('workspace_type_id')->constrained()->cascadeOnDelete();
            $table->unsignedInteger('duration_minutes'); // 30, 60, 90, 120, etc.
            $table->decimal('price', 10, 2);
            $table->date('effective_from');
            $table->date('effective_until')->nullable(); // null = still active
            $table->boolean('active')->default(true);
            $table->timestamps();

            $table->index(['workspace_type_id', 'effective_from', 'active']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pricing_rules');
    }
};
