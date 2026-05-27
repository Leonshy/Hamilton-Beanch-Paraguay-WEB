<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SiteSetting;
use App\Services\MediaService;
use Illuminate\Http\Request;

class SettingsController extends Controller
{
    public function __construct(private MediaService $mediaService) {}

    public function general()
    {
        $settings = SiteSetting::getGroup('general');
        return view('admin.settings.general', compact('settings'));
    }

    public function saveGeneral(Request $request)
    {
        $request->validate([
            'site_name'        => 'required|string|max:255',
            'site_tagline'     => 'nullable|string|max:255',
            'site_description' => 'nullable|string',
            'logo_file'        => 'nullable|image|max:2048',
            'favicon_file'     => 'nullable|file|max:512|mimes:ico,png',
        ]);

        SiteSetting::set('site_name', $request->site_name);
        SiteSetting::set('site_tagline', $request->site_tagline ?? '');
        SiteSetting::set('site_description', $request->site_description ?? '');

        if ($request->hasFile('logo_file')) {
            $media = $this->mediaService->upload($request->file('logo_file'), 'branding');
            SiteSetting::set('logo', $media->url);
        }

        if ($request->hasFile('favicon_file')) {
            $media = $this->mediaService->upload($request->file('favicon_file'), 'branding');
            SiteSetting::set('favicon', $media->url);
        }

        SiteSetting::clearCache();
        return back()->with('success', 'Configuración general guardada.');
    }

    public function contact()
    {
        $settings = SiteSetting::getGroup('contact');
        return view('admin.settings.contact', compact('settings'));
    }

    public function saveContact(Request $request)
    {
        $fields = ['phone', 'whatsapp', 'email', 'contact_email', 'address', 'schedule', 'map_embed'];
        foreach ($fields as $field) {
            SiteSetting::set($field, $request->input($field, ''));
        }
        SiteSetting::clearCache();
        return back()->with('success', 'Datos de contacto guardados.');
    }

    public function social()
    {
        $settings = SiteSetting::getGroup('social');
        return view('admin.settings.social', compact('settings'));
    }

    public function saveSocial(Request $request)
    {
        foreach (['facebook', 'instagram', 'tiktok', 'youtube', 'twitter'] as $network) {
            SiteSetting::set("social_{$network}", $request->input("social_{$network}", ''));
        }
        SiteSetting::clearCache();
        return back()->with('success', 'Redes sociales guardadas.');
    }

    public function integrations()
    {
        $settings = SiteSetting::getGroup('integrations');
        return view('admin.settings.integrations', compact('settings'));
    }

    public function saveIntegrations(Request $request)
    {
        $fields = ['google_analytics_id', 'meta_pixel_id', 'whatsapp_float_enabled', 'custom_scripts_head', 'custom_scripts_body'];
        foreach ($fields as $field) {
            SiteSetting::set($field, $request->input($field, ''));
        }
        SiteSetting::clearCache();
        return back()->with('success', 'Integraciones guardadas.');
    }
}
