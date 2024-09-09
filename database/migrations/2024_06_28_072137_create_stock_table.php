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
        Schema::create('stock', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->foreignUlid('product_id')->references('id')->on('products');                    
            $table->string('code', 3);
            $table->bigInteger('hbeli');
            $table->tinyInteger('stok');
            $table->tinyInteger('supplier_warranty');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stock');
    }
};
