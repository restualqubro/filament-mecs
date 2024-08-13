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
        'code',
        'service_id',
        'teknisi_id',
        'tot_biaya',
        'tot_disc'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customers::class);
    }

    public function detailJual(): HasMany
    {
        return $this->hasMany(DetailJual::class);
    }

    public function detailComponent(): HasMany
    {
        return $this->hasMany(DetailJual::class);
    }

    public function detailService(): HasMany
    {
        return $this->HasMany(DetailService::class);
    }
    

}
