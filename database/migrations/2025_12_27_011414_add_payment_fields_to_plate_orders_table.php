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
        Schema::table('plate_orders', function (Blueprint $table) {
            $table->enum('payment_status', ['pending', 'paid', 'failed', 'refunded'])->default('pending')->after('status');
            $table->enum('payment_method', ['stripe', 'paypal', 'cash'])->nullable()->after('payment_status');
            $table->string('stripe_payment_intent_id')->nullable()->after('payment_method');
            $table->string('stripe_session_id')->nullable()->after('stripe_payment_intent_id');
            $table->timestamp('paid_at')->nullable()->after('stripe_session_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('plate_orders', function (Blueprint $table) {
            $table->dropColumn(['payment_status', 'payment_method', 'stripe_payment_intent_id', 'stripe_session_id', 'paid_at']);
        });
    }
};
