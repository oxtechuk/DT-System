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
            $table->string('deal_number', 30)->unique();
            $table->foreignId('customer_id')->constrained()->restrictOnDelete();
            $table->unsignedBigInteger('booking_id')->nullable()->index(); // FK to bookings added in Phase 2
            $table->foreignId('room_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('workspace_type_id')->constrained()->restrictOnDelete();
            $table->timestamp('started_at');
            $table->timestamp('ended_at')->nullable();
            $table->unsignedSmallInteger('duration_minutes')->nullable();  // calculated on close
            $table->string('status')->default('open');                     // open | closed | cancelled
            $table->text('notes')->nullable();
            $table->foreignId('created_by')->constrained('users')->restrictOnDelete();
            $table->foreignId('closed_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->index(['customer_id', 'status']);
            $table->index(['room_id', 'status', 'started_at']);
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('deals');
    }
};
