<?php

namespace App\Models\Service;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DetailService extends Model
{
    use HasFactory;

    protected $table ='detail_service_catalog';
    protected $fillable = [
        'selesai_id',
        'servicecatalog_id',        
        'service_qty',
        'biaya',        
        'service_disc',                
    ];

    public function catalog(): BelongsTo
    {
        return $this->belongsTo(Catalog::class, 'servicecatalog_id', 'id');
    }

    public function selesai(): BelongsTo
    {
        return $this->belongsTo(Selesai::class);
    }
}
