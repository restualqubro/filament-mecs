<?php

namespace App\Models\Finance;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Kompensasi extends Model
{
    use HasFactory;

    protected $table = 'kompensasi';
    
    protected $fillable = [
        'category_id',
        'nominal',
        'description'
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(KompensasiCategories::class, 'category_id', 'id');
    }
}
