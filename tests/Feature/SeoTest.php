<?php

namespace Tests\Feature;

use App\Http\Middleware\RedirectWwwToApex;
use App\Models\Category;
use App\Models\Page;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Tests\TestCase;

class SeoTest extends TestCase
{
    use RefreshDatabase;

    private function category(): Category
    {
        return Category::create(['type' => 'product', 'name' => 'Cat', 'slug' => 'cat', 'is_active' => true, 'order' => 1]);
    }

    public function test_www_host_redirects_301_to_apex_preserving_path_and_query(): void
    {
        $request = Request::create('https://www.hamiltonbeach.com.py/productos?q=algo', 'GET');

        $response = (new RedirectWwwToApex())->handle($request, fn ($r) => response('no debería llegar acá'));

        $this->assertEquals(301, $response->getStatusCode());
        $this->assertEquals('https://hamiltonbeach.com.py/productos?q=algo', $response->headers->get('Location'));
    }

    public function test_apex_host_passes_through_untouched(): void
    {
        $request = Request::create('https://hamiltonbeach.com.py/productos', 'GET');

        $response = (new RedirectWwwToApex())->handle($request, fn ($r) => response('ok'));

        $this->assertEquals('ok', $response->getContent());
    }

    public function test_home_has_an_h1(): void
    {
        $this->get('/')->assertSee('<h1', false);
    }

    public function test_catalog_canonical_strips_filter_query_string(): void
    {
        $response = $this->get('/productos?categoria=cat&sort=az&q=algo');

        $response->assertSee('rel="canonical" href="' . url('/productos') . '"', false);
    }

    public function test_product_page_uses_its_own_seo_fields(): void
    {
        $product = Product::create([
            'title' => 'Producto SEO', 'slug' => 'producto-seo', 'status' => 'published', 'order' => 1,
            'category_id' => $this->category()->id,
            'meta_title' => 'Título meta personalizado',
            'meta_description' => 'Descripción meta personalizada.',
            'no_index' => true,
        ]);

        $response = $this->get(route('frontend.products.show', $product->slug));

        $response->assertSee('<title>Título meta personalizado</title>', false);
        $response->assertSee('name="description" content="Descripción meta personalizada."', false);
        $response->assertSee('name="robots" content="noindex, nofollow"', false);
        $response->assertSee('rel="canonical" href="' . route('frontend.products.show', $product->slug) . '"', false);
    }

    public function test_product_accessed_by_numeric_id_still_canonicalizes_to_slug(): void
    {
        $product = Product::create([
            'title' => 'Producto ID', 'slug' => 'producto-id', 'status' => 'published', 'order' => 1,
            'category_id' => $this->category()->id,
        ]);

        $response = $this->get('/productos/' . $product->id);

        $response->assertSee('rel="canonical" href="' . route('frontend.products.show', $product->slug) . '"', false);
    }

    public function test_product_without_seo_fields_falls_back_to_subtitle(): void
    {
        $product = Product::create([
            'title' => 'Producto Sin SEO', 'slug' => 'producto-sin-seo', 'status' => 'published', 'order' => 1,
            'category_id' => $this->category()->id,
            'subtitle' => 'Descripción corta usada como fallback de meta description.',
        ]);

        $this->get(route('frontend.products.show', $product->slug))
            ->assertSee('name="description" content="Descripción corta usada como fallback de meta description."', false);
    }

    public function test_product_schema_reflects_stock_availability_from_sale_points(): void
    {
        $category = $this->category();

        $withStock = Product::create([
            'title' => 'Con Stock', 'slug' => 'con-stock', 'status' => 'published', 'order' => 1,
            'category_id' => $category->id, 'price' => 100000, 'sku' => 'SKU-1',
            'retailers' => [['name' => 'Retailer', 'url' => 'https://ejemplo.com']],
        ]);
        $noStock = Product::create([
            'title' => 'Sin Stock', 'slug' => 'sin-stock', 'status' => 'published', 'order' => 2,
            'category_id' => $category->id, 'price' => 100000, 'sku' => 'SKU-2',
        ]);

        $this->get(route('frontend.products.show', $withStock->slug))
            ->assertSee('"availability":"https://schema.org/InStock"', false);

        $this->get(route('frontend.products.show', $noStock->slug))
            ->assertSee('"availability":"https://schema.org/OutOfStock"', false);
    }

    public function test_product_without_price_omits_offers_from_schema(): void
    {
        $product = Product::create([
            'title' => 'Sin Precio', 'slug' => 'sin-precio', 'status' => 'published', 'order' => 1,
            'category_id' => $this->category()->id,
        ]);

        $this->get(route('frontend.products.show', $product->slug))
            ->assertDontSee('"offers"', false);
    }

    public function test_page_uses_its_own_seo_fields(): void
    {
        $page = Page::create([
            'section' => 'libre', 'title' => 'Página de prueba', 'slug' => 'pagina-prueba',
            'status' => 'published', 'meta_title' => 'Meta de la página',
            'meta_description' => 'Descripción de la página.',
        ]);

        $response = $this->get(route('frontend.pages.show', $page->slug));

        $response->assertSee('<title>Meta de la página</title>', false);
        $response->assertSee('name="description" content="Descripción de la página."', false);
        $response->assertSee('rel="canonical" href="' . route('frontend.pages.show', $page->slug) . '"', false);
    }
}
