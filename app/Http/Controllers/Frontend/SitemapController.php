<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Page;
use App\Models\Product;
use Illuminate\Http\Response;

class SitemapController extends Controller
{
    private const SECTION_ROUTES = ['preguntas-frecuentes', 'servicio-tecnico', 'manuales', 'garantia'];

    public function sitemap(): Response
    {
        $products = Product::published()
            ->where('no_index', false)
            ->orderBy('updated_at', 'desc')
            ->get(['slug', 'updated_at']);

        $pages = Page::published()
            ->where('no_index', false)
            ->whereNotNull('slug')
            ->whereNotIn('section', self::SECTION_ROUTES)
            ->orderBy('updated_at', 'desc')
            ->get(['slug', 'updated_at']);

        $content = view('sitemap', compact('products', 'pages'))->render();

        return response($content, 200, [
            'Content-Type' => 'application/xml; charset=utf-8',
        ]);
    }

    public function robots(): Response
    {
        $content = view('robots')->render();

        return response($content, 200, [
            'Content-Type' => 'text/plain; charset=utf-8',
        ]);
    }
}
