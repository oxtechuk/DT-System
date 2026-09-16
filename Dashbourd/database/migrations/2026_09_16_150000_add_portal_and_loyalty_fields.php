<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('customers', function (Blueprint $table) {
            $table->string('password')->nullable()->after('email');
            $table->string('referral_code', 32)->nullable()->unique()->after('password');
            $table->foreignId('referred_by_id')->nullable()->after('referral_code')->constrained('customers')->nullOnDelete();
            $table->rememberToken()->after('referred_by_id');
        });

        Schema::table('orders', function (Blueprint $table) {
            $table->string('source', 20)->default('pos')->after('booking_id'); // pos, portal
            $table->foreignId('room_id')->nullable()->after('source')->constrained('rooms')->nullOnDelete();
            $table->string('table_or_room_name', 100)->nullable()->after('room_id');
            $table->string('fulfillment_status', 20)->default('delivered')->after('status'); // pending, preparing, delivered, cancelled
            $table->text('customer_notes')->nullable()->after('fulfillment_status');
        });
    }

    public function down(): void
    {
        Schema::table('customers', function (Blueprint $table) {
            $table->dropForeign(['referred_by_id']);
            $table->dropColumn(['password', 'referral_code', 'referred_by_id', 'remember_token']);
        });

        Schema::table('orders', function (Blueprint $table) {
            $table->dropForeign(['room_id']);
            $table->dropColumn(['source', 'room_id', 'table_or_room_name', 'fulfillment_status', 'customer_notes']);
        });
    }
};
