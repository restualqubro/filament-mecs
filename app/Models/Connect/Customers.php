<?php

namespace App\Models\Connect;

use App\Models\Service\Data;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Customers extends Model
{
    use HasFactory, HasUlids;

    protected $table = 'customers';
    protected $fillable = [
        'code',
        'name',
        'telp',
        'address',
        'type'
    ];    

    public function service(): HasMany
    {
        return $this->hasMany(Data::class, 'customer_id', 'id');
    }
}
