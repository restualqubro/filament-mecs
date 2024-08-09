<?php

namespace App\Models\Service;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\User;

class LogService extends Model
{
    use HasFactory;

    protected $table = 'service_log';
    protected $fillable = [
        'service_id',
        'status',
        'description',
        'user_id'      
    ];

    public function service(): BelongsTo
    {
        return $this->belongsTo(Data::class, 'service_id', 'id');
    }  

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
