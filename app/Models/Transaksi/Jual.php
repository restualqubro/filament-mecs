<?php

namespace App\Models\Transaksi;

use App\Models\Connect\Customers;
use App\Models\User;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Jual extends Model
{
    use HasFactory, HasUlids;

    protected $table = 'jual';    
    protected $fillable = [
        'code',
        'tanggal',
        'user_id',
        'customer_id',
        'tot_har',
        'tot_disc',
        'tot_bayar',
        'sisa',
        'status',
        'is_pending',
        'description'        
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

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($project) {
            if (empty($project->user_id)) {
                $project->user_id = auth()->id();
            }
        });
    }
}
