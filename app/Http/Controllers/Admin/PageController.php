<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Page;
use App\Traits\HandlesOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PageController extends Controller
{
    use HandlesOrder;
    public function index()
    {
        $pages = Page::orderBy('order')->paginate(20);
        return view('admin.pages.index', compact('pages'));
    }

    public function create()
    {
        $nextOrder = $this->nextOrder(Page::class);
        return view('admin.pages.form', compact('nextOrder'));
    }

    public function store(Request $request)
    {
        $data = $this->validatePage($request);
        $data['user_id'] = auth()->id();
        $data['slug'] = $this->uniqueSlug($request->slug ?: $request->title);
        $data['no_index'] = $request->boolean('no_index');
        $data['order'] = $data['order'] ?? $this->nextOrder(Page::class);
        $this->shiftOrderUp(Page::class, (int) $data['order']);
        Page::create($data);
        return redirect()->route('admin.pages.index')->with('success', 'Página creada correctamente.');
    }

    public function edit(Page $page)
    {
        return view('admin.pages.form', compact('page'));
    }

    public function update(Request $request, Page $page)
    {
        $data = $this->validatePage($request);
        if ($request->filled('slug') && $request->slug !== $page->slug) {
            $data['slug'] = $this->uniqueSlug($request->slug, $page->id);
        }
        $data['no_index'] = $request->boolean('no_index');
        $this->shiftOrderUp(Page::class, (int) $data['order'], $page->id);
        $page->update($data);
        return redirect()->route('admin.pages.index')->with('success', 'Página actualizada correctamente.');
    }

    public function destroy(Page $page)
    {
        $page->delete();
        return redirect()->route('admin.pages.index')->with('success', 'Página eliminada.');
    }

    private function validatePage(Request $request): array
    {
        return $request->validate([
            'section'          => 'required|string|max:100',
            'title'            => 'required|string|max:255',
            'subtitle'         => 'nullable|string|max:255',
            'content'          => 'nullable|string',
            'media_id'         => 'nullable|exists:media,id',
            'status'           => 'required|in:published,draft',
            'order'            => 'nullable|integer',
            'meta_title'       => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
            'og_title'         => 'nullable|string|max:255',
            'og_description'   => 'nullable|string|max:500',
            'og_image'         => 'nullable|string|max:500',
        ]);
    }

    private function uniqueSlug(string $base, ?int $ignoreId = null): string
    {
        $slug = Str::slug($base);
        $original = $slug;
        $count = 1;
        while (Page::where('slug', $slug)
            ->when($ignoreId, fn($q) => $q->where('id', '!=', $ignoreId))
            ->exists()) {
            $slug = $original . '-' . $count++;
        }
        return $slug;
    }
}
