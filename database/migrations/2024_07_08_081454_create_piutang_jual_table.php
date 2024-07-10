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
        Schema::create('piutang_jual', function (Blueprint $table) {
            $table->id();
            $table->foreignUlid('user_id')->references('id')->on('users');
            $table->foreignUlid('jual_id')->references('id')->on('jual');
            $table->date('tanggal');
            $table->bigInteger('bayar');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('piutang_jual');
    }
};
