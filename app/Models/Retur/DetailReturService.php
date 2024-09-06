<?php

namespace App\Models\Retur;

use App\Models\Service\Catalog;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DetailReturService extends Model
{
    use HasFactory;

    protected $table = 'detail_retur_service';
    protected $fillable = 
    [
        'retur_id',
        'servicecatalog_id',
        'qty',
        'biaya'
    ];

    public function catalog(): BelongsTo
    {
        return $this->belongsTo(Catalog::class, 'servicecatalog_id', 'id');
    }

    public function returservice(): BelongsTo
    {
        return $this->belongsTo(ReturService::class);
    }
}
