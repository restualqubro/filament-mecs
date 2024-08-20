<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::unprepared('
            CREATE TRIGGER tr_components_service_delete AFTER DELETE ON `detail_service_component` FOR EACH ROW
                BEGIN
                    UPDATE stock SET 
                    stock.stok = stock.stok + OLD.component_qty
                    WHERE id = OLD.stock_id;
                END
            ');
    }
    
    public function down()
    {
        DB::unprepared('DROP TRIGGER `tr_components_service_delete`');
    }
};
