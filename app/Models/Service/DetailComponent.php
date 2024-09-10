<?php

namespace App\Models\Service;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Products\Stock;

class DetailComponent extends Model
{
    use HasFactory;

    protected $table = 'detail_service_component';
    protected $fillable = [
        'selesai_id',
        'stock_id',
        'component_qty'
    ];

    public function stock(): BelongsTo
    {
        return $this->belongsTo(Stock::class);
    }
}
