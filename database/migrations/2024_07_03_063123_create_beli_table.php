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
        Schema::create('beli', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->string('code', 20)->unique();
            $table->date('tanggal');
            $table->foreignUlid('user_id')->references('id')->on('users');
            $table->foreignId('supplier_id')->references('id')->on('supplier');
            $table->bigInteger('tot_har');
            $table->bigInteger('tot_bayar');
            $table->bigInteger('ongkir');
            $table->bigInteger('sisa');
            $table->string('description')->nullable();
            $table->enum('status', ['Lunas', 'Cash', 'Utang']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('beli');
    }
};
