<?php

namespace App\Models\Transaksi;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UtangPembelian extends Model
{
    use HasFactory;
    
    protected $table = 'utang_beli';
    protected $fillable = [
        'user_id',
        'beli_id',
        'tanggal',
        'bayar'
    ];

    public function user(): BelongsTo {
        return $this->belongsTo(User::class);
    }

    public function beli(): BelongsTo {
        return $this->belongsTo(Beli::class);
    }

}
