<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Traits\HandlesOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    use HandlesOrder;
    public function index()
    {
        $categories = Category::withCount('products')->orderBy('order')->paginate(20);
        return view('admin.categories.index', compact('categories'));
    }

    public function create()
    {
        $nextOrder = $this->nextOrder(Category::class);
        return view('admin.categories.form', compact('nextOrder'));
    }

    public function store(Request $request)
    {
        $data = $this->validateCategory($request);
        $data['slug'] = $this->uniqueSlug($request->slug ?: $request->name);
        $data['is_active'] = $request->boolean('is_active');
        $data['order'] = $data['order'] ?? $this->nextOrder(Category::class);
        $this->shiftOrderUp(Category::class, (int) $data['order']);
        Category::create($data);
        return redirect()->route('admin.categories.index')->with('success', 'Categoría creada correctamente.');
    }

    public function edit(Category $category)
    {
        return view('admin.categories.form', compact('category'));
    }

    public function update(Request $request, Category $category)
    {
        $data = $this->validateCategory($request);
        if ($request->filled('slug') && $request->slug !== $category->slug) {
            $data['slug'] = $this->uniqueSlug($request->slug, $category->id);
        }
        $data['is_active'] = $request->boolean('is_active');
        $this->shiftOrderUp(Category::class, (int) $data['order'], $category->id);
        $category->update($data);
        return redirect()->route('admin.categories.index')->with('success', 'Categoría actualizada.');
    }

    public function destroy(Category $category)
    {
        if ($category->products()->count() > 0) {
            return back()->with('error', 'No se puede eliminar: tiene productos asociados.');
        }
        $category->delete();
        return redirect()->route('admin.categories.index')->with('success', 'Categoría eliminada.');
    }

    private function validateCategory(Request $request): array
    {
        $data = $request->validate([
            'name'             => 'required|string|max:255',
            'description'      => 'nullable|string',
            'order'            => 'nullable|integer',
            'meta_title'       => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
            'icon_type'        => 'nullable|in:icon,svg,image',
            'icon'             => 'nullable|string',
            'media_id'         => 'nullable|exists:media,id',
        ]);

        $data['icon_type'] = $data['icon_type'] ?? 'svg';

        if ($data['icon_type'] === 'image') {
            $data['icon'] = null;
        } elseif ($data['icon_type'] === 'svg') {
            $data['media_id'] = null;
        } else {
            $data['media_id'] = null;
        }

        return $data;
    }

    private function uniqueSlug(string $base, ?int $ignoreId = null): string
    {
        $slug = Str::slug($base);
        $original = $slug;
        $count = 1;
        while (Category::where('slug', $slug)
            ->when($ignoreId, fn($q) => $q->where('id', '!=', $ignoreId))
            ->exists()) {
            $slug = $original . '-' . $count++;
        }
        return $slug;
    }
}
