<?php

namespace App\Models\Service;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Invoice extends Model
{
    use HasFactory, HasUlids;

    protected $table = 'service_invoice';
    protected $fillable = [
        'selesai_id',
        'totalbayar',
        'sisa',
        'status',
        'description'
    ];

    public function selesai(): BelongsTo
    {
        return $this->belongsTo(Selesai::class);
    }
}
