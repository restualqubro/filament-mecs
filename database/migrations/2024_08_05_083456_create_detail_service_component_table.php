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
        Schema::create('detail_service_component', function (Blueprint $table) {
            $table->id();
            $table->foreignUlid('selesai_id')->references('id')->on('service_selesai')->cascadeOnDelete();
            $table->foreignUlid('stock_id')->references('id')->on('stock');
            $table->tinyInteger('qty');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detail_service_component');
    }
};
