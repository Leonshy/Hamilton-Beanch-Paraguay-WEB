<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Faq;
use App\Models\Page;

class PageController extends Controller
{
    public function centroAyuda()
    {
        $helpItems = Page::published()
            ->whereIn('section', ['preguntas-frecuentes', 'servicio-tecnico', 'manuales', 'garantia'])
            ->orderBy('order')
            ->get()
            ->keyBy('section');

        return view('centro-ayuda', compact('helpItems'));
    }

    public function preguntasFrecuentes()
    {
        $page = Page::published()->section('preguntas-frecuentes')->first();
        $faqs = Faq::active()->orderBy('order')->get();
        return view('preguntas-frecuentes', compact('page', 'faqs'));
    }

    public function servicioTecnico()
    {
        $page = Page::published()->section('servicio-tecnico')->first();
        return view('servicio-tecnico', compact('page'));
    }

    public function manuales()
    {
        $page = Page::published()->section('manuales')->first();
        return view('manuales-de-producto', compact('page'));
    }

    public function garantia()
    {
        $page = Page::published()->section('garantia')->first();
        return view('garantia-de-producto', compact('page'));
    }
}
