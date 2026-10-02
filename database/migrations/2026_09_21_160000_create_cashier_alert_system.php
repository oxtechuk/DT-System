<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // جدول جداول تنبيهات الكاشير (الجلسات المجدولة)
        Schema::create('cashier_alert_schedules', function (Blueprint $table) {
            $table->id();
            $table->string('title');                         // عنوان التنبيه
            $table->text('description')->nullable();         // وصف المهمة
            $table->timestamp('scheduled_at');               // وقت التنبيه الأصلي
            $table->timestamp('next_alert_at')->nullable();  // موعد التنبيه القادم
            $table->unsignedTinyInteger('snooze_count')->default(0); // عدد مرات التأجيل
            $table->enum('status', ['pending', 'snoozed', 'completed', 'dismissed'])->default('pending');
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();

            $table->index(['status', 'next_alert_at']);
        });

        // جدول سجل إجراءات تنبيهات الكاشير
        Schema::create('cashier_alert_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('schedule_id')->constrained('cashier_alert_schedules')->cascadeOnDelete();
            $table->enum('action', ['created', 'snoozed', 'completed', 'dismissed']);
            $table->unsignedSmallInteger('snoozed_minutes')->nullable(); // عدد الدقائق المؤجلة
            $table->foreignId('acted_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cashier_alert_logs');
        Schema::dropIfExists('cashier_alert_schedules');
    }
};
