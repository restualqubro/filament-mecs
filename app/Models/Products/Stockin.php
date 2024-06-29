<?php

namespace App\Models\Products;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Stockin extends Model
{
    use HasFactory;

    protected $table = 'stockin';
    protected $fillable = [
        'code',
        'tanggal',
        'category_id',
        'description',
        'user_id',
        'sumber'
    ];
}
