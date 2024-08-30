<?php

namespace App\Models\Retur;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Products\Stock;

class DetailReturJual extends Model
{
    use HasFactory;

    protected $table = 'detail_retur_jual';
    protected $fillable = [
        'retur_jual_id',
        'stock_id',
        'qty',
        'hjual',
        'disc'        
    ];

    public function returjual(): BelongsTo
    {
        return $this->belongsTo(ReturJual::class, 'retur_jual_id', 'id');
    }

    public function stock(): BelongsTo
    {
        return $this->belongsTo(Stock::class);
    }
}
