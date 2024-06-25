<?php

namespace App\Models\Connect;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    use HasFactory;

    protected $table = 'supplier';
    protected $fillable = [
        'name',
        'telp',
        'address'
    ];
}
