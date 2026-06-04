<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $categories = Category::active()->ofType('product')->orderBy('order')->get();

        $query = Product::with('featuredImage', 'category')->published();

        if ($request->filled('q')) {
            $search = $request->q;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('subtitle', 'like', "%{$search}%")
                  ->orWhere('sku', 'like', "%{$search}%");
            });
        }

        if ($request->filled('categoria')) {
            $query->whereHas('category', fn($q) => $q->where('slug', $request->categoria));
        }

        $sort = $request->get('sort', 'relevancia');
        match ($sort) {
            'az'      => $query->orderBy('title'),
            'za'      => $query->orderByDesc('title'),
            'nuevo'   => $query->orderByDesc('created_at'),
            default   => $query->orderBy('order'),
        };

        $products = $query->paginate(12)->withQueryString();

        return view('productos', compact('products', 'categories'));
    }

    public function show(string $id)
    {
        // Soporta tanto slug como id para compatibilidad
        $product = Product::with('featuredImage', 'category', 'gallery')
            ->published()
            ->where(is_numeric($id) ? 'id' : 'slug', $id)
            ->firstOrFail();

        $related = Product::with('featuredImage')
            ->published()
            ->where('id', '!=', $product->id)
            ->when($product->category_id, fn($q) => $q->where('category_id', $product->category_id))
            ->orderBy('order')
            ->take(3)
            ->get();

        return view('producto-detalle', compact('product', 'related'));
    }
}
