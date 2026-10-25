<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('teacher_visits', function (Blueprint $table) {
            $table->id();
            $table->foreignId('teacher_id')->constrained()->cascadeOnDelete();
            $table->foreignId('customer_id')->nullable()->constrained()->nullOnDelete();
            $table->string('visitor_name')->comment('اسم الزائر');
            $table->string('visitor_phone')->nullable()->comment('هاتف الزائر');
            $table->timestamp('visited_at')->useCurrent()->comment('وقت الزيارة');
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('teacher_visits');
    }
};
