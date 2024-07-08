<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{

    public function up(): void
    {        
        DB::unprepared('
        CREATE TRIGGER tr_jual_edit AFTER UPDATE ON `detail_jual` FOR EACH ROW
                BEGIN                    
                    UPDATE stock SET 
                    stock.stok = stock.stok + OLD.qty - NEW.qty
                    WHERE id = NEW.stock_id;
                END
        ');
    }
    
    public function down()
    {
        DB::unprepared('DROP TRIGGER `tr_jual_add`');
    }
};
