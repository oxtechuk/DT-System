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
        // 1. Order Items discount
        if (Schema::hasTable('order_items') && !Schema::hasColumn('order_items', 'discount_amount')) {
            Schema::table('order_items', function (Blueprint $table) {
                $table->decimal('discount_amount', 10, 2)->default(0)->after('unit_price');
            });
        }

        // 2. Payments overpayment handling (Tip vs Debt Credit)
        if (Schema::hasTable('payments') && !Schema::hasColumn('payments', 'overpayment_type')) {
            Schema::table('payments', function (Blueprint $table) {
                $table->enum('overpayment_type', ['none', 'tip', 'debt_credit'])->default('none')->after('type');
                $table->decimal('overpayment_amount', 10, 2)->default(0)->after('overpayment_type');
            });
        }

        // 3. Staff Notifications recurrence
        if (Schema::hasTable('staff_notifications') && !Schema::hasColumn('staff_notifications', 'notification_type')) {
            Schema::table('staff_notifications', function (Blueprint $table) {
                $table->enum('notification_type', ['once', 'recurring'])->default('once')->after('color');
                $table->string('recurrence_pattern')->default('none')->after('notification_type'); // none, daily, weekly, monthly
            });
        }

        // 4. Customer Feedback & Ratings & Problem Reports
        if (!Schema::hasTable('customer_feedbacks')) {
            Schema::create('customer_feedbacks', function (Blueprint $table) {
                $table->id();
                $table->foreignId('customer_id')->constrained()->cascadeOnDelete();
                $table->foreignId('deal_id')->nullable()->constrained()->nullOnDelete();
                $table->unsignedTinyInteger('rating')->nullable(); // 1 to 5 stars
                $table->text('comment')->nullable();
                $table->boolean('is_issue_report')->default(false);
                $table->enum('status', ['pending', 'reviewed', 'resolved'])->default('pending');
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('order_items') && Schema::hasColumn('order_items', 'discount_amount')) {
            Schema::table('order_items', function (Blueprint $table) {
                $table->dropColumn('discount_amount');
            });
        }

        if (Schema::hasTable('payments') && Schema::hasColumn('payments', 'overpayment_type')) {
            Schema::table('payments', function (Blueprint $table) {
                $table->dropColumn(['overpayment_type', 'overpayment_amount']);
            });
        }

        if (Schema::hasTable('staff_notifications') && Schema::hasColumn('staff_notifications', 'notification_type')) {
            Schema::table('staff_notifications', function (Blueprint $table) {
                $table->dropColumn(['notification_type', 'recurrence_pattern']);
            });
        }

        Schema::dropIfExists('customer_feedbacks');
    }
};
