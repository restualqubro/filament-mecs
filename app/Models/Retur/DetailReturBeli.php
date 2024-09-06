<?php

namespace App\Models\Retur;

use App\Models\Products\Stock;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DetailReturBeli extends Model
{
    use HasFactory;

    protected $table = 'detail_retur_beli';
    protected $fillable = [
        'retur_beli_id',
        'stock_id',
        'qty',
        'hjual',
        'disc'        
    ];

    public function returjual(): BelongsTo
    {
        return $this->belongsTo(ReturBeli::class, 'retur_beli_id', 'id');
    }

    public function stock(): BelongsTo
    {
        return $this->belongsTo(Stock::class);
    }
}
