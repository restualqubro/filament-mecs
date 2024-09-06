<?php

namespace App\Models\Retur;

use App\Models\Service\Invoice;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ReturService extends Model
{
    use HasFactory;

    protected $table = 'retur_service';
    protected $fillable = [
        'code',
        'user_id',
        'invoice_id', 
        'totalbiaya',
        'description'
    ];

    protected function invoice(): BelongsTo
    {
        return $this->belongsTo(Invoice::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function detailRetur(): HasMany
    {
        return $this->hasMany(DetailReturService::class, 'retur_service_id', 'id');
    }

}
