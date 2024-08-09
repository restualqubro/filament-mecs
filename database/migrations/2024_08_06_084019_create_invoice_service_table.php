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
        Schema::create('invoice_service', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->foreignUlid('selesai_id')->references('id')->on('service_selesai');
            $table->bigInteger('subtotal');
            $table->bigInteger('tot_disc');
            $table->bigInteger('total');
            $table->bigInteger('tot_bayar');
            $table->bigInteger('sisa');
            $table->enum('status', ['Cash', 'Lunas', 'Piutang']);
            $table->string('description');            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoice_service');
    }
};
