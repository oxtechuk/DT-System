<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Customers indexes for registration and active queries
        Schema::table('customers', function (Blueprint $table) {
            $table->index('created_at', 'idx_customers_created_at');
            $table->index('last_active_at', 'idx_customers_last_active_at');
            $table->index('classification', 'idx_customers_classification');
        });

        // 2. Deals indexes for period analytics and room tracking
        Schema::table('deals', function (Blueprint $table) {
            $table->index(['started_at', 'ended_at'], 'idx_deals_dates');
            $table->index('customer_id', 'idx_deals_customer_id');
            $table->index('room_id', 'idx_deals_room_id');
            $table->index('status', 'idx_deals_status');
        });

        // 3. Orders indexes for period reporting
        Schema::table('orders', function (Blueprint $table) {
            $table->index(['created_at', 'status'], 'idx_orders_created_status');
            $table->index('source', 'idx_orders_source');
        });

        // 4. Payments indexes for daily revenue
        Schema::table('payments', function (Blueprint $table) {
            $table->index(['paid_at', 'type'], 'idx_payments_paid_type');
            $table->index('method', 'idx_payments_method');
        });
    }

    public function down(): void
    {
        Schema::table('customers', function (Blueprint $table) {
            $table->dropIndex('idx_customers_created_at');
            $table->dropIndex('idx_customers_last_active_at');
            $table->dropIndex('idx_customers_classification');
        });

        Schema::table('deals', function (Blueprint $table) {
            $table->dropIndex('idx_deals_dates');
            $table->dropIndex('idx_deals_customer_id');
            $table->dropIndex('idx_deals_room_id');
            $table->dropIndex('idx_deals_status');
        });

        Schema::table('orders', function (Blueprint $table) {
            $table->dropIndex('idx_orders_created_status');
            $table->dropIndex('idx_orders_source');
        });

        Schema::table('payments', function (Blueprint $table) {
            $table->dropIndex('idx_payments_paid_type');
            $table->dropIndex('idx_payments_method');
        });
    }
};
