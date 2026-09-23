<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class SiteSetting extends Model
{
    protected $fillable = ['key', 'value', 'type', 'group', 'label', 'description'];

    public static function get(string $key, mixed $default = null): mixed
    {
        return Cache::remember("site_setting_{$key}", 3600, function () use ($key, $default) {
            $setting = static::where('key', $key)->first();
            return $setting ? $setting->value : $default;
        });
    }

    public static function set(string $key, mixed $value): void
    {
        static::updateOrCreate(['key' => $key], ['value' => $value]);
        Cache::forget("site_setting_{$key}");
    }

    public static function getGroup(string $group): array
    {
        return static::where('group', $group)->pluck('value', 'key')->toArray();
    }

    /**
     * Invalida solo la caché relacionada a la configuración del sitio.
     *
     * Antes usaba Cache::flush(), que borraba TODA la caché de la app —
     * incluidos los contadores de rate limiting del login y del formulario
     * de contacto — cada vez que se guardaba cualquier pantalla de
     * Configuración (incluso Contacto o Redes sociales).
     */
    public static function clearCache(): void
    {
        foreach (static::pluck('key') as $key) {
            Cache::forget("site_setting_{$key}");
        }

        Cache::forget('nav.site_settings');
        Cache::forget('nav.announcements');
        Cache::forget('nav.footer_pages');
        Cache::forget('nav.categories');
    }
}
