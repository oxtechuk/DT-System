<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Workspace Types (Shared, Private, Meeting Room, etc.)
        Schema::create('workspace_types', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('code')->unique();        // shared, private, meeting
            $table->enum('pricing_mode', ['duration', 'fixed', 'hourly'])->default('duration');
            $table->boolean('active')->default(true);
            $table->timestamps();
        });

        // Rooms
        Schema::create('rooms', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('code')->nullable()->unique();
            $table->unsignedInteger('capacity')->nullable();
            $table->enum('status', ['active', 'maintenance', 'inactive'])->default('active');
            $table->string('color')->nullable();     // hex color for calendar display
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rooms');
        Schema::dropIfExists('workspace_types');
    }
};
