<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    
    public function up(): void
    {
        DB::unprepared('
            CREATE TRIGGER tr_products_service_delete AFTER DELETE ON `detail_service_jual` FOR EACH ROW
                BEGIN
                    UPDATE stock SET 
                    stock.stok = stock.stok + OLD.products_qty
                    WHERE id = OLD.stock_id;
                END
            ');
    }
    
    public function down()
    {
        DB::unprepared('DROP TRIGGER `tr_products_service_delete`');
    }
};
