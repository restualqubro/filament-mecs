<?php

namespace App\Models\Transaksi;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KeuanganCategories extends Model
{
    use HasFactory;

    protected $table = 'keuangan_categories';
    protected $fillable = [
        'name',
        'jenis'
    ];
}
