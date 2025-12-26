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
        Schema::create('vehicles', function (Blueprint $table) {
            $table->id();
            $table->string('make');           // ex: "Renault"
            $table->string('model');          // ex: "Clio V"
            $table->integer('year');
            $table->enum('type', ['sale', 'rent']);
            $table->decimal('price', 10, 2)->nullable();      // Prix vente
            $table->decimal('rent_price', 8, 2)->nullable();  // Prix location/jour
            $table->string('fuel');           // ex: "Hybride"
            $table->string('transmission');   // ex: "Auto"
            $table->integer('mileage')->nullable();
            $table->integer('seats')->nullable();
            $table->json('images');           // Tableau d'URLs stocké en JSON
            $table->string('badge_type')->nullable(); // 'stock', 'promo', 'lastchance'
            $table->integer('badge_value')->nullable();
            $table->boolean('is_available')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vehicles');
    }
};
