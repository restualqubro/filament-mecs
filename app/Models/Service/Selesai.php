<?php

namespace App\Models\Service;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\User;
use App\Models\Connect\Customers;
use Illuminate\Database\Eloquent\Concerns\HasUlids;

class Selesai extends Model
{
    use HasFactory, HasUlids;

    protected $table = 'service_selesai';

    protected $fillable = [        
        'service_id',
        'teknisi_id',
        'subtotal_products',
        'totaldiscount_products',
        'subtotal_service',
        'totaldiscount_service',
        'subtotal_component',
        'total'
    ];

    public function teknisi(): BelongsTo
    {
        return $this->belongsTo(User::class, 'teknisi_id', 'id');
    }

    public function service(): BelongsTo
    {
        return $this->belongsTo(Data::class, 'service_id', 'id');
    }

    public function detailJual(): HasMany
    {
        return $this->hasMany(DetailJual::class);
    }

    public function detailComponent(): HasMany
    {
        return $this->hasMany(DetailComponent::class);
    }

    public function detailService(): HasMany
    {
        return $this->HasMany(DetailService::class);
    }  
    

}
