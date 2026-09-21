<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('staff_notifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();     // المستلم
            $table->foreignId('sent_by')->nullable()->constrained('users')->nullOnDelete(); // المرسل
            $table->string('title');
            $table->text('body')->nullable();
            $table->string('icon')->default('bell');   // bell | task | warning | info
            $table->string('color')->default('green'); // green | blue | amber | red
            $table->timestamp('scheduled_at');         // موعد الظهور
            $table->timestamp('read_at')->nullable();  // وقت القراءة
            $table->timestamps();

            $table->index(['user_id', 'scheduled_at', 'read_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('staff_notifications');
    }
};
