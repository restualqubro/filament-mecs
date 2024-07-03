<?php

namespace App\Models\Transaksi;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailBeli extends Model
{
    use HasFactory;

    protected $table ='detail_beli';
    protected $fillable = [
        'beli_id',
        'product_id',
        'name',
        'qty',
        'hbeli',
        'warranty',
    ];
}
