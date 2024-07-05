<?php

namespace App\Models\Transaksi;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\User;
use App\Models\Connect\Supplier;
use Spatie\Image\Enums\Fit;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Beli extends Model implements HasMedia 
{
    use InteractsWithMedia;
    use HasFactory, HasUlids;

    protected $table = 'beli';    
    protected $fillable = [
        'code',
        'tanggal',
        'user_id',
        'supplier_id',
        'tot_har',
        'ongkir',
        'tot_bayar',        
        'sisa',
        'description',
        'status'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class);
    }

    public function detailBeli(): HasMany
    {
        return $this->hasMany(DetailBeli::class);
    }

    public function getFilamentMediaUrl(): ?string
    {
        return $this->getMedia('beli')?->first()?->getUrl() ?? $this->getMedia('beli')?->first()?->getUrl('thumb') ?? null;
    }

    public function registerMediaConversions(?Media $media = null): void
    {
        $this
            ->addMediaConversion('preview')
            ->fit(Fit::Contain, 300, 300)
            ->nonQueued();
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
