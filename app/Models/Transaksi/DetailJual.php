<?php

namespace App\Models\Transaksi;

use App\Models\Products\Stock;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DetailJual extends Model
{
    use HasFactory;

    protected $table ='detail_jual';
    protected $fillable = [
        'jual_id',
        'stock_id',        
        'qty',
        'hjual',        
        'disc',
        'profit',
        'warranty'
    ];

    public function jual(): BelongsTo
    {
        return $this->belongsTo(Jual::class);
    }

    public function stock(): BelongsTo
    {
        return $this->belongsTo(Stock::class);
    }

    public function getJumlahAttribute()
    {
        return $this->qty * ($this->hjual - $this->disc);
    }
}
