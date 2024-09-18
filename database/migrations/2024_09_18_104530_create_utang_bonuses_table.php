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
        Schema::create('utang_bonus', function (Blueprint $table) {
            $table->id();
            $table->foreignUlid('catalog_id')->references('id')->on('service_catalog');
            $table->unsignedBigInteger('nominal');
            $table->foreignUlid('user_id')->references('id')->on('users');
            $table->enum('status', ['unpaid', 'paid']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('utang_bonuses');
    }
};
