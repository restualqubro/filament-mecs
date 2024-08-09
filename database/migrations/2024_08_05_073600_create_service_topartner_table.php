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
        Schema::create('service_topartner', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->foreignUlid('service_id')->references('id')->on('service_data');
            $table->foreignId('partner_id')->references('id')->on('partners');
            $table->date('date_send');
            $table->enum('status', ['Kirim', 'Proses', 'Cancel', 'Selesai', 'Kembali']);
            $table->date('date_update')->nullable();
            $table->string('update')->nullable();
            $table->unsignedBigInteger('biaya')->nullable();
            $table->boolean('is_Lunas')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('service_topartner');
    }
};
