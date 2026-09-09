<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('rooms', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('code', 20)->nullable()->unique();
            $table->unsignedTinyInteger('capacity')->nullable(); // for shared capacity rules
            $table->string('status')->default('active');         // active | maintenance | inactive
            $table->string('color', 20)->nullable();             // hex color for calendar
            $table->text('description')->nullable();
            $table->timestamps();

            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rooms');
    }
};
