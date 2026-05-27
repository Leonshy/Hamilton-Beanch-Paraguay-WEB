<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Media;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with('category', 'featuredImage')->orderBy('order')->paginate(20);
        return view('admin.products.index', compact('products'));
    }

    public function create()
    {
        $categories = Category::active()->ofType('product')->orderBy('name')->get();
        return view('admin.products.form', compact('categories'));
    }

    public function store(Request $request)
    {
        $data = $this->validateProduct($request);
        $data['user_id'] = auth()->id();
        $data['slug'] = $this->uniqueSlug($request->slug ?: $request->title);
        $data['specifications'] = $this->parseSpecifications($request->specifications_json);
        $data['is_featured'] = $request->boolean('is_featured');
        $data['no_index'] = $request->boolean('no_index');

        $product = Product::create($data);

        // Galería
        $this->syncGallery($product, $request->input('gallery_ids', []));

        return redirect()->route('admin.products.index')->with('success', 'Producto creado correctamente.');
    }

    public function edit(Product $product)
    {
        $categories = Category::active()->ofType('product')->orderBy('name')->get();
        $product->load('gallery');
        return view('admin.products.form', compact('product', 'categories'));
    }

    public function update(Request $request, Product $product)
    {
        $data = $this->validateProduct($request, $product->id);
        if ($request->filled('slug') && $request->slug !== $product->slug) {
            $data['slug'] = $this->uniqueSlug($request->slug, $product->id);
        }
        $data['specifications'] = $this->parseSpecifications($request->specifications_json);
        $data['is_featured'] = $request->boolean('is_featured');
        $data['no_index'] = $request->boolean('no_index');

        $product->update($data);
        $this->syncGallery($product, $request->input('gallery_ids', []));

        return redirect()->route('admin.products.index')->with('success', 'Producto actualizado correctamente.');
    }

    public function destroy(Product $product)
    {
        $product->delete();
        return redirect()->route('admin.products.index')->with('success', 'Producto eliminado.');
    }

    private function validateProduct(Request $request, ?int $ignoreId = null): array
    {
        return $request->validate([
            'title'            => 'required|string|max:255',
            'subtitle'         => 'nullable|string|max:255',
            'content'          => 'nullable|string',
            'category_id'      => 'nullable|exists:categories,id',
            'media_id'         => 'nullable|exists:media,id',
            'status'           => 'required|in:published,draft',
            'order'            => 'nullable|integer',
            'price'            => 'nullable|integer|min:0',
            'attachment'       => 'nullable|string|max:500',
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
        while (Product::where('slug', $slug)
            ->when($ignoreId, fn($q) => $q->where('id', '!=', $ignoreId))
            ->exists()) {
            $slug = $original . '-' . $count++;
        }
        return $slug;
    }

    private function parseSpecifications(?string $json): ?array
    {
        if (!$json) return null;
        $decoded = json_decode($json, true);
        return is_array($decoded) ? $decoded : null;
    }

    private function syncGallery(Product $product, array $ids): void
    {
        $sync = [];
        foreach (array_filter($ids) as $order => $id) {
            $sync[$id] = ['order' => $order];
        }
        $product->gallery()->sync($sync);
    }
}
