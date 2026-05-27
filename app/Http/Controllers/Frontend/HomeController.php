<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use App\Models\Category;
use App\Models\Product;

class HomeController extends Controller
{
    public function index()
    {
        $banner = Banner::with('image')
            ->active()
            ->position('home')
            ->orderBy('order')
            ->first();

        $featuredProducts = Product::with('featuredImage', 'category')
            ->published()
            ->featured()
            ->orderBy('order')
            ->take(6)
            ->get();

        $categories = Category::with('image')
            ->active()
            ->ofType('product')
            ->orderBy('order')
            ->get();

        return view('index', compact('banner', 'featuredProducts', 'categories'));
    }
}
