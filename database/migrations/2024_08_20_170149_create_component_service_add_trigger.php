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
        CREATE TRIGGER tr_components_service_add AFTER INSERT ON `detail_service_component` FOR EACH ROW
                BEGIN                    
                    UPDATE stock SET                     
                    stock.stok = stock.stok - NEW.component_qty
                    WHERE id = NEW.stock_id;
                END
        ');
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        DB::unprepared('DROP TRIGGER `tr_components_service_add`');
    }
};
