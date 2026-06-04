<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use App\Models\Category;
use App\Models\Product;
use App\Models\SalePoint;

class HomeController extends Controller
{
    public function index()
    {
        $banners = Banner::with('image')
            ->active()
            ->position('home')
            ->orderBy('order')
            ->get();

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

        $midBanners = Banner::with('image')
            ->active()
            ->position('home_mid')
            ->orderBy('order')
            ->get();

        $salePoints = SalePoint::with('logo')->active()->inRandomOrder()->get();

        $banner = $banners->first();

        return view('index', compact('banners', 'banner', 'midBanners', 'featuredProducts', 'categories', 'salePoints'));
    }
}
