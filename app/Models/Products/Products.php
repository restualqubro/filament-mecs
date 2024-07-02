<?php

namespace App\Models\Products;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
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
        'code',
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

    public function stock(): BelongsToMany
    {
        return $this->belongsToMany(Stock::class);
    }  
    
    public function getSumAttribute()
    {
        $sum = 0;
        $get = Stock::select('stok')->where('product_id', $this->id)->get();                
        foreach($get as $stok)
        {
            $sum = $sum + $stok->stok;
        }
        return $sum;
    }
}
