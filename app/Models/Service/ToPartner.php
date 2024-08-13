<?php

namespace App\Models\Service;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Connect\Partner;
use App\Models\Service\Data;

class ToPartner extends Model
{
    use HasFactory, HasUlids;

    protected $table = 'service_topartner';
    protected $fillable = [
        'service_id',
        'partner_id',
        'date_send',
        'status',        
        'update',
        'biaya',
        'status_pembayaran'
    ];

    public function service(): BelongsTo
    {
        return $this->belongsTo(Data::class, 'service_id', 'id');
    }

    public function partner(): BelongsTo
    {
        return $this->belongsTo(Partner::class, 'partner_id', 'id');
    }
}
