<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Announcement extends Model
{
    protected $fillable = ['text', 'order', 'is_active'];

    protected $casts = ['is_active' => 'boolean'];

    protected static function booted(): void
    {
        static::saved(fn () => Cache::forget('nav.announcements'));
        static::deleted(fn () => Cache::forget('nav.announcements'));
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
