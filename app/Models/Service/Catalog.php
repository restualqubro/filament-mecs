<?php

namespace App\Models\Service;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Catalog extends Model
{
    use HasFactory, HasUlids;

    protected $table = 'service_catalog';

    protected $fillable = [
        'name',
        'biaya_min',
        'biaya_max',
        'bonus',
        'warranty'
    ];
}
