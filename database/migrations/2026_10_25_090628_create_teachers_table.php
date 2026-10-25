<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('teachers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->decimal('commission_rate', 5, 2)->default(10.00)->comment('نسبة الكوميشن %');
            $table->string('qr_code')->unique()->comment('كود QR الفريد للمدرس');
            $table->text('bio')->nullable()->comment('نبذة عن المدرس');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('teachers');
    }
};
