<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('workspace_types', function (Blueprint $table) {
            $table->id();
            $table->string('name');                    // Shared, Private, Meeting Room, etc.
            $table->string('code', 30)->unique();      // shared, private
            $table->string('pricing_mode')->default('duration'); // duration | flat | hourly
            $table->boolean('active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('workspace_types');
    }
};
