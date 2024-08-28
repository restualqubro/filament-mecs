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
        Schema::create('detail_retur_jual', function (Blueprint $table) {
            $table->id();
            $table->foreignUlid('retur_jual_id')->references('id')->on('retur_jual');
            $table->foreignUlid('stock_id')->references('id')->on('stock');
            $table->tinyInteger('qty');
            $table->bigInteger('hjual');
            $table->bigInteger('disc');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detail_retur_jual');
    }
};
