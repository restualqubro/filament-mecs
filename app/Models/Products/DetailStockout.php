<?php

namespace App\Models\Products;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailStockout extends Model
{
    use HasFactory;

    protected $table = 'detail_stockout';
    protected $fillable = [
        'stockout_id',
        'stock_id',
        'name',
        'qty'
    ];

    public function stock(){
        return $this->belongsTo(Stock::class);
    }
    
    public function product(){
        return $this->belongsTo(Products::class);
    }
}
