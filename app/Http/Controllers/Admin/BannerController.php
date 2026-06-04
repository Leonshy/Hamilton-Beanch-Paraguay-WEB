<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use App\Models\SiteSetting;
use App\Traits\HandlesOrder;
use Illuminate\Http\Request;

class BannerController extends Controller
{
    use HandlesOrder;
    public function index()
    {
        $grouped = Banner::with('image')->orderBy('position')->orderBy('order')->get()->groupBy('position');
        return view('admin.banners.index', compact('grouped'));
    }

    public function create()
    {
        $position = request('position', 'home');
        $nextOrder = $this->nextOrder(Banner::class, 'position', $position);
        $nextOrderByPosition = [
            'home'     => $this->nextOrder(Banner::class, 'position', 'home'),
            'home_mid' => $this->nextOrder(Banner::class, 'position', 'home_mid'),
        ];
        return view('admin.banners.form', compact('nextOrder', 'nextOrderByPosition'));
    }

    public function store(Request $request)
    {
        $data = $this->validateBanner($request);
        $data['is_active'] = $request->boolean('is_active');
        $data['order'] = $data['order'] ?? $this->nextOrder(Banner::class, 'position', $data['position']);
        $this->shiftOrderUp(Banner::class, (int) $data['order'], null, 'position', $data['position']);
        Banner::create($data);
        $this->saveInterval($request);
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
        $this->shiftOrderUp(Banner::class, (int) $data['order'], $banner->id, 'position', $data['position']);
        $banner->update($data);
        $this->saveInterval($request);
        return redirect()->route('admin.banners.index')->with('success', 'Banner actualizado correctamente.');
    }

    public function reorder(Request $request)
    {
        $data = $request->validate([
            'ids'      => 'required|array',
            'ids.*'    => 'integer',
            'position' => 'required|in:home,home_mid',
        ]);
        // Sólo reordenar IDs que pertenecen a esa posición
        $validIds = Banner::whereIn('id', $data['ids'])->where('position', $data['position'])->pluck('id')->all();
        $ordered  = array_values(array_filter($data['ids'], fn($id) => in_array($id, $validIds)));
        $this->applyReorder(Banner::class, $ordered);
        return response()->json(['ok' => true]);
    }

    public function destroy(Banner $banner)
    {
        $banner->delete();
        return redirect()->route('admin.banners.index')->with('success', 'Banner eliminado.');
    }

    private function saveInterval(Request $request): void
    {
        if ($request->filled('hero_slide_interval')) {
            SiteSetting::set('hero_slide_interval', max(2, (int) $request->hero_slide_interval));
            SiteSetting::clearCache();
        }
    }

    private function validateBanner(Request $request): array
    {
        return $request->validate([
            'position' => 'required|in:home,home_mid',
            'order'    => 'nullable|integer',
            'media_id' => 'nullable|exists:media,id',
            'link_url' => 'nullable|url|max:500',
        ]);
    }
}
