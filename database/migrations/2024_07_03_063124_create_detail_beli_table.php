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
        Schema::create('detail_beli', function (Blueprint $table) {
            $table->id();
            $table->foreignUlid('beli_id')->references('id')->on('beli');
            $table->foreignUlid('product_id')->references('id')->on('products');
            $table->tinyInteger('qty');
            $table->string('name');
            $table->bigInteger('hbeli');
            $table->tinyInteger('supplier_warranty');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detail_beli');
    }
};
