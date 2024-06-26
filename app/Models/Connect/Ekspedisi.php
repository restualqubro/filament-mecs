<?php

namespace App\Models\Connect;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Image\Enums\Fit;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Ekspedisi extends Model implements HasMedia
{
    use InteractsWithMedia;
    use HasFactory;

    protected $table = 'ekspedisi';
    protected $fillable = [
        'name',
        'jenis'
    ];

    public function getFilamentMediaUrl(): ?string
    {
        return $this->getMedia('ekspedisi')?->first()?->getUrl() ?? $this->getMedia('ekspedisi')?->first()?->getUrl('thumb') ?? null;
    }

    public function registerMediaConversions(?Media $media = null): void
    {
        $this
            ->addMediaConversion('preview')
            ->fit(Fit::Contain, 300, 300)
            ->nonQueued();
    }
}
