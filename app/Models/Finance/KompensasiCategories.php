<?php

namespace App\Models\Finance;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KompensasiCategories extends Model
{
    use HasFactory;

    protected $table = 'kompensasi_categories';

    protected $fillable = [
        'name'
    ];
}
