<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Customers HubSpot fields
        if (Schema::hasTable('customers') && !Schema::hasColumn('customers', 'hubspot_contact_id')) {
            Schema::table('customers', function (Blueprint $table) {
                $table->string('hubspot_contact_id')->nullable()->index()->after('notes');
                $table->timestamp('hubspot_synced_at')->nullable()->after('hubspot_contact_id');
            });
        }

        // 2. Deals HubSpot fields
        if (Schema::hasTable('deals') && !Schema::hasColumn('deals', 'hubspot_deal_id')) {
            Schema::table('deals', function (Blueprint $table) {
                $table->string('hubspot_deal_id')->nullable()->index()->after('notes');
                $table->timestamp('hubspot_synced_at')->nullable()->after('hubspot_deal_id');
            });
        }

        // 3. Bookings HubSpot fields
        if (Schema::hasTable('bookings') && !Schema::hasColumn('bookings', 'hubspot_deal_id')) {
            Schema::table('bookings', function (Blueprint $table) {
                $table->string('hubspot_deal_id')->nullable()->index()->after('notes');
                $table->timestamp('hubspot_synced_at')->nullable()->after('hubspot_deal_id');
            });
        }

        // 4. Products HubSpot fields
        if (Schema::hasTable('products') && !Schema::hasColumn('products', 'hubspot_product_id')) {
            Schema::table('products', function (Blueprint $table) {
                $table->string('hubspot_product_id')->nullable()->index()->after('description');
                $table->timestamp('hubspot_synced_at')->nullable()->after('hubspot_product_id');
            });
        }

        // 5. HubSpot Sync Logs table
        if (!Schema::hasTable('hubspot_sync_logs')) {
            Schema::create('hubspot_sync_logs', function (Blueprint $table) {
                $table->id();
                $table->string('entity_type', 50); // customer, deal, product, general
                $table->unsignedBigInteger('entity_id')->nullable();
                $table->string('hubspot_id')->nullable();
                $table->string('action', 50); // push, pull, update, delete, test
                $table->enum('status', ['success', 'failed', 'pending'])->default('pending');
                $table->text('payload')->nullable();
                $table->text('response')->nullable();
                $table->text('error_message')->nullable();
                $table->timestamps();

                $table->index(['entity_type', 'entity_id']);
                $table->index('status');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hubspot_sync_logs');

        if (Schema::hasColumn('customers', 'hubspot_contact_id')) {
            Schema::table('customers', function (Blueprint $table) {
                $table->dropColumn(['hubspot_contact_id', 'hubspot_synced_at']);
            });
        }

        if (Schema::hasColumn('deals', 'hubspot_deal_id')) {
            Schema::table('deals', function (Blueprint $table) {
                $table->dropColumn(['hubspot_deal_id', 'hubspot_synced_at']);
            });
        }

        if (Schema::hasColumn('bookings', 'hubspot_deal_id')) {
            Schema::table('bookings', function (Blueprint $table) {
                $table->dropColumn(['hubspot_deal_id', 'hubspot_synced_at']);
            });
        }

        if (Schema::hasColumn('products', 'hubspot_product_id')) {
            Schema::table('products', function (Blueprint $table) {
                $table->dropColumn(['hubspot_product_id', 'hubspot_synced_at']);
            });
        }
    }
};
