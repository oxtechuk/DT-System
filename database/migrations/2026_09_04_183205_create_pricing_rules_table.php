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
            $table->unsignedSmallInteger('duration_minutes'); // 30, 60, 90, 120 ...
            $table->decimal('price', 10, 2);
            $table->date('effective_from');
            $table->date('effective_until')->nullable();      // null = currently active
            $table->boolean('active')->default(true);
            $table->timestamps();

            // Ensure one active rule per type+duration at any time
            $table->index(['workspace_type_id', 'duration_minutes', 'effective_from'], 'pr_type_dur_eff_idx');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pricing_rules');
    }
};
