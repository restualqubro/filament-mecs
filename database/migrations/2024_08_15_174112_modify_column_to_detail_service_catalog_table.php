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
        Schema::table('detail_service_catalog', function (Blueprint $table) {
            $table->renameColumn('qty', 'service_qty');
            $table->renameColumn('disc', 'service_disc');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('detail_service_catalog', function (Blueprint $table) {
            //
        });
    }
};
