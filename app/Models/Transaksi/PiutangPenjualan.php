<?php

namespace App\Models\Transaksi;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PiutangPenjualan extends Model
{
    use HasFactory;

    protected $table = 'piutang_jual';
    protected $fillable = [
        'user_id',
        'jual_id',
        'tanggal',
        'bayar'
    ];

    public function user(): BelongsTo {
        return $this->belongsTo(User::class);
    }

    public function jual(): BelongsTo {
        return $this->belongsTo(Jual::class);
    }
}
