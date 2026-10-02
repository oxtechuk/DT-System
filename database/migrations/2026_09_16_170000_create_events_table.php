<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('category')->default('ورشة عمل'); // ورشة عمل، ملتقى، ندوة، استشارات
            $table->string('badge_text')->nullable()->default('مجاناً للأعضاء');
            $table->text('description')->nullable();
            $table->string('speaker_name')->nullable();
            $table->string('speaker_title')->nullable();
            $table->string('location')->default('القاعة الرئيسية — DDT');
            $table->date('event_date');
            $table->string('time_text')->default('06:00 م - 08:30 م');
            $table->decimal('price', 8, 2)->default(0);
            $table->string('banner_theme')->default('green'); // green, sage, charcoal
            $table->string('registration_url')->nullable();
            $table->integer('capacity')->default(25);
            $table->boolean('is_featured')->default(false);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};
