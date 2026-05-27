<?php

namespace Database\Seeders;

use App\Models\SiteSetting;
use Illuminate\Database\Seeder;

class SiteSettingsSeeder extends Seeder
{
    public function run(): void
    {
        $settings = [
            // General
            ['key' => 'site_name',        'value' => 'Hamilton Beach Paraguay', 'type' => 'text',  'group' => 'general', 'label' => 'Nombre del sitio'],
            ['key' => 'site_description', 'value' => 'Distribuidor oficial en Paraguay. Electrodomésticos de calidad con respaldo y servicio técnico oficial.', 'type' => 'text', 'group' => 'general', 'label' => 'Descripción del sitio'],
            ['key' => 'logo',             'value' => '',    'type' => 'file', 'group' => 'general', 'label' => 'Logo'],
            ['key' => 'favicon',          'value' => '',    'type' => 'file', 'group' => 'general', 'label' => 'Favicon'],

            // Contact
            ['key' => 'phone',          'value' => '+595 (9) 1234-567',        'type' => 'text',  'group' => 'contact', 'label' => 'Teléfono'],
            ['key' => 'whatsapp',       'value' => '595911234567',             'type' => 'text',  'group' => 'contact', 'label' => 'WhatsApp'],
            ['key' => 'email',          'value' => 'info@hamiltonbeach.com.py','type' => 'email', 'group' => 'contact', 'label' => 'Email público'],
            ['key' => 'contact_email',  'value' => 'info@hamiltonbeach.com.py','type' => 'email', 'group' => 'contact', 'label' => 'Email de recepción de formularios'],
            ['key' => 'address',        'value' => 'Asunción, Paraguay',       'type' => 'text',  'group' => 'contact', 'label' => 'Dirección'],
            ['key' => 'schedule',       'value' => "Lun–Vie: 9:00 – 18:00 hs\nSáb: 10:00 – 14:00 hs", 'type' => 'textarea', 'group' => 'contact', 'label' => 'Horarios'],

            // Social
            ['key' => 'instagram', 'value' => 'https://instagram.com/hamiltonbeachpy', 'type' => 'url', 'group' => 'social', 'label' => 'Instagram'],
            ['key' => 'facebook',  'value' => 'https://facebook.com/hamiltonbeachpy',  'type' => 'url', 'group' => 'social', 'label' => 'Facebook'],
            ['key' => 'tiktok',    'value' => 'https://tiktok.com/@hamiltonbeachpy',   'type' => 'url', 'group' => 'social', 'label' => 'TikTok'],
            ['key' => 'youtube',   'value' => '',                                       'type' => 'url', 'group' => 'social', 'label' => 'YouTube'],
            ['key' => 'twitter',   'value' => '',                                       'type' => 'url', 'group' => 'social', 'label' => 'Twitter / X'],

            // Integrations
            ['key' => 'google_analytics_id',   'value' => '',   'type' => 'text',     'group' => 'integrations', 'label' => 'Google Analytics ID'],
            ['key' => 'meta_pixel_id',         'value' => '',   'type' => 'text',     'group' => 'integrations', 'label' => 'Meta Pixel ID'],
            ['key' => 'whatsapp_float_enabled','value' => '1',  'type' => 'boolean',  'group' => 'integrations', 'label' => 'WhatsApp flotante activo'],
            ['key' => 'custom_scripts_head',   'value' => '',   'type' => 'textarea', 'group' => 'integrations', 'label' => 'Scripts en <head>'],
            ['key' => 'custom_scripts_body',   'value' => '',   'type' => 'textarea', 'group' => 'integrations', 'label' => 'Scripts antes de </body>'],
        ];

        foreach ($settings as $setting) {
            SiteSetting::updateOrCreate(
                ['key' => $setting['key']],
                $setting
            );
        }
    }
}
