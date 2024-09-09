<?php

namespace App\Models\Transaksi;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Pemasukan extends Model
{
    use HasFactory;

    protected $table = 'pemasukan';
    protected $fillable = [
        'category_id',
        'nominal',
        'submitted_id',
        'description'
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(KeuanganCategories::class, 'category_id', 'id');
    }

    public function submitted(): BelongsTo
    {
        return $this->belongsTo(User::class, 'submitted_id', 'id');
    }
}
