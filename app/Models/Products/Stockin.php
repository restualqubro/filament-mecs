<?php

namespace App\Models\Products;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

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

    public function detailStockin(): HasMany
    {
        return $this->hasMany(DetailStockin::class);
    }
}
