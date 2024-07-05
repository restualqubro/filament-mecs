<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {        
        DB::unprepared('
        CREATE TRIGGER tr_beli_add AFTER INSERT ON `detail_beli` FOR EACH ROW
                BEGIN                    
                    UPDATE stock SET 
                    stock.hbeli = NEW.hbeli,
                    stock.stok = stock.stok + NEW.qty
                    WHERE id = NEW.stock_id;
                END
        ');
    }
    
    public function down()
    {
        DB::unprepared('DROP TRIGGER `tr_beli_add`');
    }
};
