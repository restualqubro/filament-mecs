<?php

namespace App\Models\Service;

use App\Models\Connect\Customers;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Data extends Model
{
    use HasFactory;

    protected $table = 'service_data';
    protected $fillable = [
        'date_in',
        'customer_id',
        'category_id',
        'merk',
        'seri',
        'sn',
        'kelengkapan',
        'keluhan',
        'description',
        'status',
        'penawaran'
    ];

    public function customer(): BelongsTo {
        return $this->belongsTo(Customers::class);
    }

    public function category(): BelongsTo {
        return $this->belongsTo(Categories::class);
    }
}
