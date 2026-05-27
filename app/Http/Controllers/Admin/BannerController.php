<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use Illuminate\Http\Request;

class BannerController extends Controller
{
    public function index()
    {
        $banners = Banner::with('image')->orderBy('position')->orderBy('order')->paginate(20);
        return view('admin.banners.index', compact('banners'));
    }

    public function create()
    {
        return view('admin.banners.form');
    }

    public function store(Request $request)
    {
        $data = $this->validateBanner($request);
        $data['is_active'] = $request->boolean('is_active');
        Banner::create($data);
        return redirect()->route('admin.banners.index')->with('success', 'Banner creado correctamente.');
    }

    public function edit(Banner $banner)
    {
        return view('admin.banners.form', compact('banner'));
    }

    public function update(Request $request, Banner $banner)
    {
        $data = $this->validateBanner($request);
        $data['is_active'] = $request->boolean('is_active');
        $banner->update($data);
        return redirect()->route('admin.banners.index')->with('success', 'Banner actualizado correctamente.');
    }

    public function destroy(Banner $banner)
    {
        $banner->delete();
        return redirect()->route('admin.banners.index')->with('success', 'Banner eliminado.');
    }

    private function validateBanner(Request $request): array
    {
        return $request->validate([
            'title'       => 'nullable|string|max:255',
            'subtitle'    => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'cta_text'    => 'nullable|string|max:100',
            'cta_url'     => 'nullable|string|max:500',
            'position'    => 'required|in:home,productos',
            'order'       => 'nullable|integer',
            'media_id'    => 'nullable|exists:media,id',
        ]);
    }
}
