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
        CREATE TRIGGER tr_stockout_edit AFTER UPDATE ON `detail_stockout` FOR EACH ROW
            BEGIN
                UPDATE stock SET stock.stok = stock.stok + OLD.qty - NEW.qty
                    WHERE stock.id = NEW.stock_id;
            END
        ');
    }

    public function down()
    {
        DB::unprepared('DROP TRIGGER `tr_stockout_edit`');
    }
};
