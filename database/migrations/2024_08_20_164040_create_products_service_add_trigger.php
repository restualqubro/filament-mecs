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
        CREATE TRIGGER tr_products_service_add AFTER INSERT ON `detail_service_jual` FOR EACH ROW
                BEGIN                    
                    UPDATE stock SET                     
                    stock.stok = stock.stok - NEW.products_qty
                    WHERE id = NEW.stock_id;
                END
        ');
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        DB::unprepared('DROP TRIGGER `tr_products_service_add`');
    }
};
