<?php

namespace App\Models\Retur;

use App\Models\Transaksi\Jual;
use App\Models\User;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ReturJual extends Model
{
    use HasFactory, HasUlids;

    protected $table = 'retur_jual';
    protected $fillable = [
        'code',
        'jual_id',
        'user_id',
        'totalharga',
        'totaldiscount',
        'description'
    ];

    public function jual(): BelongsTo
    {
        return $this->belongsTo(Jual::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function detailRetur(): HasMany
    {
        return $this->hasMany(DetailReturJual::class, 'retur_jual_id', 'id');
    }
}
