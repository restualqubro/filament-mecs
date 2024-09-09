<?php

namespace App\Models\Transaksi;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Pengeluaran extends Model
{
    use HasFactory;

    protected $table = 'pengeluaran';
    protected $fillable = [
        'category_id',
        'nominal',
        'status',
        'submitted_id',
        'approval_id',
        'description'
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(KeuanganCategories::class);
    }

    public function submitted(): BelongsTo
    {
        return $this->belongsTo(User::class, 'submitted_id', 'id');
    }

    public function approval(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approval_id', 'id');
    }
}
