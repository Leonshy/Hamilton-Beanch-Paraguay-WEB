<?php

namespace App\Helpers;

use App\Models\SiteSetting;

class SettingsHelper
{
    public static function get(string $key, mixed $default = null): mixed
    {
        return SiteSetting::get($key, $default);
    }

    public static function siteName(): string
    {
        return static::get('site_name', 'Hamilton Beach Paraguay');
    }

    public static function logo(): ?string
    {
        return static::get('logo');
    }

    public static function favicon(): ?string
    {
        return static::get('favicon');
    }

    public static function phone(): string
    {
        return static::get('phone', '+595 (9) 1234-567');
    }

    public static function whatsapp(): string
    {
        return static::get('whatsapp', '595911234567');
    }

    public static function email(): string
    {
        return static::get('email', 'info@hamiltonbeach.com.py');
    }

    public static function contactEmail(): string
    {
        return static::get('contact_email', 'info@hamiltonbeach.com.py');
    }

    public static function address(): string
    {
        return static::get('address', 'Asunción, Paraguay');
    }

    public static function schedule(): string
    {
        return static::get('schedule', 'Lun–Vie: 09:00–18:00 | Sáb: 10:00–14:00');
    }

    public static function social(string $network): ?string
    {
        return static::get("social_{$network}");
    }

    public static function analytics(): ?string
    {
        return static::get('google_analytics_id');
    }

    public static function whatsappFloat(): bool
    {
        return (bool) static::get('whatsapp_float_enabled', true);
    }
}
