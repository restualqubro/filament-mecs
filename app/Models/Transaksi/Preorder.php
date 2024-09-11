<?php

namespace App\Models\Transaksi;

use App\Models\Connect\Customers;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;

class Preorder extends Model
{
    use HasFactory;

    protected $table = 'preorder';
    protected $fillable = [
        'code',
        'customer_id',
        'user_id',
        'nominal',
        'description',        
        'estimasi',
        'status'
    ];

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customers::class);
    }    

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($project) {
            if (empty($project->user_id)) {
                $project->user_id = auth()->id();
            }
        });
    }
}
