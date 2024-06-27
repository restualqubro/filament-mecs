<?php

namespace App\Models\Products;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Image\Enums\Fit;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Products extends Model implements HasMedia
{
    use InteractsWithMedia;
    use HasFactory, HasUlids;

    protected $table = 'products';
    protected $fillable = [
        'name', 
        'category_id',
        'brand_id',
        'kondisi',
        'hress',
        'hjual',
        'sale_warranty'
    ];

    public function getFilamentMediaUrl(): ?string
    {
        return $this->getMedia('products')?->first()?->getUrl() ?? $this->getMedia('products')?->first()?->getUrl('thumb') ?? null;
    }

    public function registerMediaConversions(?Media $media = null): void
    {
        $this
            ->addMediaConversion('preview')
            ->fit(Fit::Contain, 600, 600)
            ->nonQueued();
    }
}
