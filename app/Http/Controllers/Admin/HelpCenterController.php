<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Page;
use Illuminate\Http\Request;

class HelpCenterController extends Controller
{
    private const SECTIONS = ['preguntas-frecuentes', 'servicio-tecnico', 'manuales', 'garantia'];

    public function index()
    {
        $items = Page::whereIn('section', self::SECTIONS)
            ->orderBy('order')
            ->get()
            ->keyBy('section');

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
