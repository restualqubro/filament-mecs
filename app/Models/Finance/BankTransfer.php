<?php

namespace App\Models\Finance;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BankTransfer extends Model
{
    use HasFactory;

    protected $table = 'bank_transfer';
    protected $fillable = [
        'nominal', 
        'type', 
        'account_id',
        'description'
    ];

    public function account(): BelongsTo
    {
        return $this->belongsTo(BankAccount::class, 'account_id', 'id');
    }
}
