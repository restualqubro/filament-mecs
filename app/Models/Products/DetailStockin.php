<?php

namespace App\Models\Products;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DetailStockin extends Model
{
    use HasFactory;

    protected $table = 'detail_stockin';
    protected $fillable = [
        'stockin_id',
        'stock_id',
        'qty'
    ];

    public function stock(){
        return $this->belongsTo(Stock::class);
    }
    
    public function product(){
        return $this->belongsTo(Products::class);
    }
    
}
