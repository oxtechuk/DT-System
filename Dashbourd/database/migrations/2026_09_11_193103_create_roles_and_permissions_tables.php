<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Roles
        Schema::create('roles', function (Blueprint $table) {
            $table->id();
            $table->string('name');           // Owner, Manager, Reception, etc.
            $table->string('slug')->unique();
            $table->string('description')->nullable();
            $table->timestamps();
        });

        // Permissions
        Schema::create('permissions', function (Blueprint $table) {
            $table->id();
            $table->string('name');           // customers.view, deals.create, etc.
            $table->string('group')->nullable(); // customers, deals, orders, etc.
            $table->string('description')->nullable();
            $table->timestamps();
        });

        // Role <-> Permission pivot
        Schema::create('permission_role', function (Blueprint $table) {
            $table->foreignId('permission_id')->constrained()->cascadeOnDelete();
            $table->foreignId('role_id')->constrained()->cascadeOnDelete();
            $table->primary(['permission_id', 'role_id']);
        });

        // User <-> Role pivot
        Schema::create('role_user', function (Blueprint $table) {
            $table->foreignId('role_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->primary(['role_id', 'user_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('role_user');
        Schema::dropIfExists('permission_role');
        Schema::dropIfExists('permissions');
        Schema::dropIfExists('roles');
    }
};
