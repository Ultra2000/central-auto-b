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
        Schema::create('custom_requests', function (Blueprint $table) {
            $table->id();
            $table->enum('type', ['achat', 'location', 'vente', 'expertise'])->default('achat');
            $table->string('category')->nullable();
            $table->string('budget')->nullable();
            $table->string('brand')->nullable();
            $table->string('model')->nullable();
            $table->string('transmission')->nullable();
            $table->string('name');
            $table->string('email');
            $table->string('phone');
            $table->text('message')->nullable();
            $table->enum('status', ['pending', 'processing', 'completed', 'cancelled'])->default('pending');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('custom_requests');
    }
};
