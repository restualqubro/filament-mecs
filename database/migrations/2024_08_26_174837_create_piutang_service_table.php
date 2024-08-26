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
        Schema::create('piutang_service', function (Blueprint $table) {
            $table->id();
            $table->foreignUlid('invoice_id')->references('id')->on('service_invoice');
            $table->bigInteger('bayar');
            $table->foreignUlid('user_id')->references('id')->on('users');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('piutang_service');
    }
};
