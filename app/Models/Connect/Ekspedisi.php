<?php

namespace App\Models\Connect;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Ekspedisi extends Model implements HasMedia
{
    use InteractsWithMedia;
    use HasFactory;

    protected $table = 'ekspedisi';
    protected $fillable = [
        'name',
        'jenis'
    ];
}
