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
        $data['specifications'] = $request->input('specifications');
        $data['retailers'] = $this->parseRetailers($request->input('retailers', []));
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
        $data['specifications'] = $request->input('specifications');
        $data['retailers'] = $this->parseRetailers($request->input('retailers', []));
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
            'specifications'   => 'nullable|string',
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

    private function parseRetailers(array $retailers): ?array
    {
        $result = [];
        $names = $retailers['name'] ?? [];
        $urls  = $retailers['url']  ?? [];
        foreach ($names as $i => $name) {
            $name = trim($name);
            if ($name === '') continue;
            $result[] = ['name' => $name, 'url' => trim($urls[$i] ?? '')];
        }
        return $result ?: null;
    }

    private function parseSpecifications(?string $json): ?array
    {
        if (!$json) return null;
        $decoded = json_decode($json, true);
        return is_array($decoded) ? $decoded : null;
    }

    private function syncGallery(Product $product, array $ids): void
    {
        // El input viene como un string "1,2,3" dentro de un array — hay que aplanar
        $flat = [];
        foreach ($ids as $item) {
            foreach (array_filter(array_map('trim', explode(',', (string) $item))) as $id) {
                if (is_numeric($id)) {
                    $flat[] = (int) $id;
                }
            }
        }

        $sync = [];
        foreach ($flat as $order => $id) {
            $sync[$id] = ['order' => $order];
        }
        $product->gallery()->sync($sync);
    }
}
