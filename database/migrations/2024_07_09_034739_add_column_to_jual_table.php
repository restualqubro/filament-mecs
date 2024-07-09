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
        Schema::table('jual', function (Blueprint $table) {
            $table->foreignId('preorder_id')->references('id')->on('preorder')->nullable();
            $table->bigInteger('tot_pr');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('jual', function (Blueprint $table) {
            //
        });
    }
};
