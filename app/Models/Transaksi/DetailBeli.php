<?php

namespace App\Models\Transaksi;

use App\Models\Products\Stock;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DetailBeli extends Model
{
    use HasFactory;

    protected $table ='detail_beli';
    protected $fillable = [
        'beli_id',
        'stock_id',        
        'hbeli',
        'qty',        
        'hbeli',
        'supplier_warranty',
    ];

    public function beli(): BelongsTo
    {
        return $this->belongsTo(Beli::class);
    }

    public function stock(): BelongsTo
    {
        return $this->belongsTo(Stock::class);
    }

    public function getJumlahAttribute()
    {
        return ($this->qty * $this->hbeli);
    }
}
