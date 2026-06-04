<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id', 'category_id', 'media_id', 'title', 'subtitle', 'content',
        'slug', 'sku', 'status', 'order', 'is_featured', 'attachment', 'price', 'specifications', 'retailers',
        'meta_title', 'meta_description', 'og_title', 'og_description', 'og_image', 'no_index',
    ];

    protected $casts = [
        'is_featured' => 'boolean',
        'no_index' => 'boolean',
        'retailers' => 'array',
        'price' => 'integer',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function featuredImage(): BelongsTo
    {
        return $this->belongsTo(Media::class, 'media_id');
    }

    public function gallery(): BelongsToMany
    {
        return $this->belongsToMany(Media::class, 'product_media')
            ->withPivot('order')
            ->orderBy('product_media.order');
    }

    public function getFormattedPriceAttribute(): string
    {
        if (!$this->price) return '';
        return 'Gs. ' . number_format($this->price, 0, ',', '.');
    }

    public function scopePublished($query)
    {
        return $query->where('status', 'published');
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }
}
