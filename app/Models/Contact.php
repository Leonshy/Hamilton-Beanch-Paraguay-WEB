<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    protected $fillable = [
        'first_name', 'last_name', 'phone', 'email', 'subject',
        'message', 'newsletter_consent', 'ip_address', 'status', 'notes',
    ];

    protected $casts = [
        'newsletter_consent' => 'boolean',
    ];

    public function getFullNameAttribute(): string
    {
        return "{$this->first_name} {$this->last_name}";
    }

    public function scopeNew($query)
    {
        return $query->where('status', 'new');
    }
}
