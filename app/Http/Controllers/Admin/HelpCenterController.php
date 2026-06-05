<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Page;
use Illuminate\Http\Request;

class HelpCenterController extends Controller
{
    private const SECTIONS = ['preguntas-frecuentes', 'servicio-tecnico', 'manuales', 'garantia'];

    private const DEFAULTS = [
        'preguntas-frecuentes' => [
            'title'    => 'Preguntas frecuentes',
            'subtitle' => 'Respuestas a las dudas más comunes',
            'icon'     => 'M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z',
            'order'    => 0,
        ],
        'servicio-tecnico' => [
            'title'    => 'Servicio Técnico',
            'subtitle' => 'Reparaciones con repuestos originales',
            'icon'     => 'M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z||M15 12a3 3 0 11-6 0 3 3 0 016 0z',
            'order'    => 1,
        ],
        'manuales' => [
            'title'    => 'Manuales',
            'subtitle' => 'Guías de uso de todos los productos',
            'icon'     => 'M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253',
            'order'    => 2,
        ],
        'garantia' => [
            'title'    => 'Garantías',
            'subtitle' => 'Póliza y condiciones de garantía oficial',
            'icon'     => 'M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z',
            'order'    => 3,
        ],
    ];

    public function index()
    {
        $items = Page::whereIn('section', self::SECTIONS)->get()->keyBy('section');

        // Crear o restaurar registros faltantes con valores por defecto
        foreach (self::SECTIONS as $section) {
            if (!$items->has($section)) {
                $defaults = self::DEFAULTS[$section];
                // Buscar incluyendo soft-deleted para no violar el unique de slug
                $page = Page::withTrashed()->where('section', $section)->first();
                if ($page) {
                    $page->restore();
                    $page->update([
                        'title'    => $page->title ?: $defaults['title'],
                        'subtitle' => $page->subtitle ?: $defaults['subtitle'],
                        'icon'     => $page->icon ?: $defaults['icon'],
                        'status'   => 'published',
                    ]);
                } else {
                    $page = Page::create([
                        'section'  => $section,
                        'title'    => $defaults['title'],
                        'subtitle' => $defaults['subtitle'],
                        'icon'     => $defaults['icon'],
                        'status'   => 'published',
                        'order'    => $defaults['order'],
                        'slug'     => $section,
                    ]);
                }
                $items->put($section, $page);
            }
        }

        return view('admin.help-center.index', compact('items'));
    }

    public function update(Request $request, Page $page)
    {
        $data = $request->validate([
            'title'    => 'required|string|max:255',
            'subtitle' => 'nullable|string|max:500',
            'icon'     => 'nullable|string',
        ]);

        $page->update($data);

        $title = $page->title;
        return back()->with('success', "\"$title\" actualizado correctamente.");
    }
}
