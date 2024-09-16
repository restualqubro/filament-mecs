<?php

namespace App\Models\Service;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Concerns\HasUlids;

class Garansi extends Model
{
    use HasFactory, HasUlids;

    protected $table = 'service_garansi';
    protected $fillable = [
        'code',
        'invoice_id',
        'kelengkapan',
        'keluhan',
        'status', 
        'update',
        'user_id'
    ];

    public function invoice(): BelongsTo
    {
        return $this->belongsTo(Invoice::class);
    }
}
