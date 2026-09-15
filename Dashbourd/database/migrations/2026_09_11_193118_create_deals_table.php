<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('deals', function (Blueprint $table) {
            $table->id();
            $table->string('deal_number')->unique();
            $table->foreignId('customer_id')->constrained()->restrictOnDelete();
            $table->foreignId('booking_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('room_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('workspace_type_id')->constrained()->restrictOnDelete();

            // Pricing snapshot (preserved even if pricing rules change)
            $table->foreignId('pricing_rule_id')->nullable()->constrained('pricing_rules')->nullOnDelete();
            $table->decimal('applied_price', 10, 2)->nullable(); // price locked at deal close

            // Time tracking
            $table->dateTime('started_at');
            $table->dateTime('ended_at')->nullable();
            $table->unsignedInteger('duration_minutes')->nullable();
            $table->unsignedInteger('billable_duration_minutes')->nullable(); // after pricing engine

            // Status
            $table->enum('status', ['open', 'closed', 'cancelled', 'pending', 'no_show'])->default('open');
            $table->text('notes')->nullable();

            // Audit
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('closed_by')->nullable()->constrained('users')->nullOnDelete();

            $table->timestamps();
            $table->softDeletes();

            $table->index(['customer_id', 'status']);
            $table->index(['room_id', 'status', 'started_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('deals');
    }
};
