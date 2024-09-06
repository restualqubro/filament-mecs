<?php

namespace App\Models\Retur;

use App\Models\Transaksi\Beli;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ReturBeli extends Model
{
    use HasFactory;

    protected $table = 'retur_beli';
    protected $fillable = [
        'code',
        'beli_id',
        'user_id',
        'totalharga',        
        'description'
    ];

    public function beli(): BelongsTo
    {
        return $this->belongsTo(Beli::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function detailRetur(): HasMany
    {
        return $this->hasMany(DetailReturBeli::class, 'retur_beli_id', 'id');
    }
}
