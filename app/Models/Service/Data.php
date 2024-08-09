<?php

namespace App\Models\Service;

use App\Models\Connect\Customers;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Data extends Model
{
    use HasFactory, HasUlids;

    protected $table = 'service_data';
    protected $fillable = [
        'code',
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

    public function logservice(): HasMany {
        return $this->hasMany(LogService::class, 'service_id', 'id');
    }

    public function toPartner(): HasOne {
        return $this->hasOne(ToPartner::class);
    }
    
}
