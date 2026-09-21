<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('customers', function (Blueprint $table) {
            $table->string('classification', 30)->default('freelancer')->after('customer_type');
            $table->timestamp('last_active_at')->nullable()->after('status');
            $table->index(['classification']);
            $table->index(['last_active_at']);
        });
    }

    public function down(): void
    {
        Schema::table('customers', function (Blueprint $table) {
            $table->dropIndex(['classification']);
            $table->dropIndex(['last_active_at']);
            $table->dropColumn(['classification', 'last_active_at']);
        });
    }
};
