<?php

namespace App\Models\Service;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\User;
use App\Models\Service\Data;
use Illuminate\Database\Eloquent\Concerns\HasUlids;

class Cancel extends Model
{
    use HasFactory, HasUlids;
    
    protected $table = 'service_cancel';
    protected $fillable = [
        'service_id',
        'teknisi_id',
        'alasan',
        'isKeluar'
    ];

    public function teknisi(): BelongsTo
    {
        return $this->belongsTo(User::class, 'teknisi_id', 'id')   ;
    }

    public function service(): BelongsTo
    {
        return $this->belongsTo(Data::class, 'service_id', 'id');
    }
}
