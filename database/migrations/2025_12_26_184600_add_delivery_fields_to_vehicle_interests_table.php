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
        Schema::table('vehicle_interests', function (Blueprint $table) {
            $table->enum('delivery_option', ['pickup', 'delivery'])->default('pickup')->after('message');
            $table->string('delivery_address')->nullable()->after('delivery_option');
            $table->string('delivery_city')->nullable()->after('delivery_address');
            $table->string('delivery_postal_code')->nullable()->after('delivery_city');
            $table->decimal('delivery_cost', 8, 2)->default(0)->after('delivery_postal_code');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('vehicle_interests', function (Blueprint $table) {
            $table->dropColumn(['delivery_option', 'delivery_address', 'delivery_city', 'delivery_postal_code', 'delivery_cost']);
        });
    }
};
