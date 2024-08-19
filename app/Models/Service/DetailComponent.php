<?php

namespace App\Models\Service;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailComponent extends Model
{
    use HasFactory;

    protected $table = 'detail_service_component';
    protected $fillable = [
        'selesai_id',
        'stock_id',
        'component_qty'
    ];
}
