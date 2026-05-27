<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Page extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id', 'media_id', 'section', 'title', 'subtitle', 'icon', 'content',
        'slug', 'status', 'order',
        'meta_title', 'meta_description', 'og_title', 'og_description', 'og_image', 'no_index',
    ];

    protected $casts = [
        'no_index' => 'boolean',
    ];

    public function featuredImage(): BelongsTo
    {
        return $this->belongsTo(Media::class, 'media_id');
    }

    public function scopePublished($query)
    {
        return $query->where('status', 'published');
    }

    public function scopeSection($query, string $section)
    {
        return $query->where('section', $section);
    }
}
