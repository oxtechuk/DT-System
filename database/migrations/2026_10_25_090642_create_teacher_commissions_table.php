<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('teacher_commissions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('teacher_id')->constrained()->cascadeOnDelete();
            $table->foreignId('teacher_visit_id')->constrained()->cascadeOnDelete();
            $table->decimal('amount', 10, 2)->default(0)->comment('مبلغ الكوميشن');
            $table->decimal('commission_rate_snapshot', 5, 2)->comment('نسبة الكوميشن وقت الزيارة');
            $table->decimal('visit_base_value_snapshot', 10, 2)->comment('قيمة الزيارة الأساسية وقت التسجيل');
            $table->enum('status', ['pending', 'paid', 'cancelled'])->default('pending');
            $table->timestamp('paid_at')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('teacher_commissions');
    }
};
