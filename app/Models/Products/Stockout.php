<?php

namespace App\Models\Products;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Stockout extends Model
{
    use HasFactory, HasUlids;

    protected $table = 'stockout';
    protected $fillable = [
        'code',
        'tanggal',
        'category_id',
        'description',
        'user_id',        
    ];

    public function detailStockout(): HasMany
    {
        return $this->hasMany(DetailStockout::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(StockCategories::class);
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($project) {
            if (empty($project->user_id)) {
                $project->user_id = auth()->id();
            }
        });
    }
}
