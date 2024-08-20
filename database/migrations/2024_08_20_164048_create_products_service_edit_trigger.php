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
        CREATE TRIGGER tr_products_service_edit AFTER UPDATE ON `detail_service_jual` FOR EACH ROW
                BEGIN                    
                    UPDATE stock SET 
                    stock.stok = stock.stok + OLD.products_qty - NEW.products_qty
                    WHERE id = NEW.stock_id;
                END
        ');
    }
    
    public function down()
    {
        DB::unprepared('DROP TRIGGER `tr_products_service_edit`');
    }
};
