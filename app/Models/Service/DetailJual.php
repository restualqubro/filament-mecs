<?php

namespace App\Models\Service;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Products\Stock;

class DetailJual extends Model
{
    use HasFactory;

    protected $table = 'detail_service_jual';
    protected $fillable = [
        'selesai_id',
        'stock_id',
        'qty',
        'hjual',
        'disc',
        'profit'
    ];

    public function selesai(): BelongsTo
    {
        return $this->belongsTo(Selesai::class);
    }

    public function stock(): BelongsTo
    {
        return $this->belongsTo(Stock::class);
    }
}
