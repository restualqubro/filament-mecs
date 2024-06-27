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
        Schema::create('products', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->string('name');
            $table->string('code', 12);
            $table->foreignId('category_id')->references('id')->on('product_categories');
            $table->foreignId('brand_id')->references('id')->on('product_brands');
            $table->bigInteger('hress');
            $table->bigInteger('hjual');
            $table->enum('kondisi', ['BARU', 'SECOND']);
            $table->tinyInteger('sale_warranty')->nullable();
            $table->string('description')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
