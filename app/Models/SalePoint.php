<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SalePoint extends Model
{
    protected $fillable = ['media_id', 'name', 'url', 'is_active', 'order'];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function logo(): BelongsTo
    {
        return $this->belongsTo(Media::class, 'media_id');
    }

    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'product_sale_point')
            ->withPivot('custom_url');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
