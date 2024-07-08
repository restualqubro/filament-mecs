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
        Schema::create('jual', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->string('code', 20);
            $table->date('tanggal');
            $table->foreignUlid('user_id')->references('id')->on('users');
            $table->foreignUlid('customer_id')->references('id')->on('customers');            
            $table->bigInteger('tot_har');
            $table->bigInteger('tot_disc');
            $table->bigInteger('tot_bayar');
            $table->bigInteger('sisa');
            $table->enum('status', ['Lunas', 'Cash', 'Piutang']);
            $table->boolean('is_Pending');
            $table->string('description');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jual');
    }
};
