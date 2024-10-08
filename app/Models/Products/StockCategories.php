<?php

namespace App\Models\Products;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockCategories extends Model
{
    use HasFactory;

    protected $table = 'stock_categories';
    protected $fillable = 
    [
        'name',
        'jenis'
    ];
}
