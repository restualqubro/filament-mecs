<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::unprepared('
            CREATE TRIGGER tr_stockin_edit AFTER UPDATE ON `detail_stockin` FOR EACH ROW
                BEGIN
                    UPDATE stock SET stock.stok = stock.stok - OLD.qty + NEW.qty
                    WHERE id = NEW.stock_id;
                END
            ');
        }
    
        public function down()
        {
            DB::unprepared('DROP TRIGGER `tr_stockin_edit`');
        }
};
