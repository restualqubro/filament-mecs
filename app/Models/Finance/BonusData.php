<?php

namespace App\Models\Finance;

use App\Models\Service\Catalog;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BonusData extends Model
{
    use HasFactory;

    protected $table = 'service_bonuses';

    protected $fillable = [
        'catalog_id',
        'nominal',
        'user_id'
    ];

    public function catalog(): BelongsTo
    {
        return $this->belongsTo(Catalog::class, 'catalog_id', 'id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
