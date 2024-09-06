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
        Schema::create('detail_retur_service', function (Blueprint $table) {
            $table->id();
            $table->foreignUlid('retur_service_id')->references('id')->on('retur_service');
            $table->foreignUlid('service_catalog_id')->references('id')->on('service_catalog');
            $table->tinyInteger('qty');
            $table->bigInteger('biaya');
            $table->bigInteger('disc')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detail_retur_service');
    }
};
