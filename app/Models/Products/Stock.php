<?php

namespace App\Models\Products;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Stock extends Model
{
    use HasFactory, HasUlids;

    protected $table = 'stock';
    protected $fillable = [
        'code',
        'product_id',
        'supplier_warranty',
        'hbeli',
        'stok',
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Products::class);
    }   
    
    public function getFullcodeAttribute()
    {
        return "{$this->product->code}-{$this->code}";
    }
}
