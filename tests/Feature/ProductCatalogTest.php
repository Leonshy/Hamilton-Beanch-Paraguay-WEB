<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductCatalogTest extends TestCase
{
    use RefreshDatabase;

    public function test_product_list_shows_published_products(): void
    {
        $category = Category::create([
            'type' => 'product', 'name' => 'Cafeteras', 'slug' => 'cafeteras', 'is_active' => true, 'order' => 1,
        ]);

        Product::create([
            'category_id' => $category->id, 'title' => 'Cafetera Test', 'slug' => 'cafetera-test',
            'status' => 'published', 'order' => 1,
        ]);

        Product::create([
            'category_id' => $category->id, 'title' => 'Producto Borrador', 'slug' => 'producto-borrador',
            'status' => 'draft', 'order' => 2,
        ]);

        $response = $this->get(route('frontend.products.index'));

        $response->assertStatus(200);
        $response->assertSee('Cafetera Test');
        $response->assertDontSee('Producto Borrador');
    }

    public function test_product_detail_page_shows_published_product(): void
    {
        $product = Product::create([
            'title' => 'Licuadora Test', 'slug' => 'licuadora-test', 'status' => 'published', 'order' => 1,
        ]);

        $this->get(route('frontend.products.show', $product->slug))
            ->assertStatus(200)
            ->assertSee('Licuadora Test');
    }

    public function test_draft_product_is_not_accessible_by_slug(): void
    {
        $product = Product::create([
            'title' => 'Producto Oculto', 'slug' => 'producto-oculto', 'status' => 'draft', 'order' => 1,
        ]);

        $this->get(route('frontend.products.show', $product->slug))->assertStatus(404);
    }

    public function test_search_treats_percent_sign_as_a_literal_character_not_a_wildcard(): void
    {
        Product::create(['title' => 'Freidora 50% descuento', 'slug' => 'freidora-50', 'status' => 'published', 'order' => 1]);
        Product::create(['title' => 'Freidora normal', 'slug' => 'freidora-normal', 'status' => 'published', 'order' => 2]);

        $response = $this->get(route('frontend.products.index', ['q' => '50%']));

        $response->assertSee('Freidora 50% descuento');
        $response->assertDontSee('Freidora normal');
    }

    public function test_search_treats_underscore_as_a_literal_character_not_a_wildcard(): void
    {
        Product::create(['title' => 'Modelo A_1', 'slug' => 'modelo-a-1', 'status' => 'published', 'order' => 1]);
        Product::create(['title' => 'Modelo AX1', 'slug' => 'modelo-ax1', 'status' => 'published', 'order' => 2]);

        $response = $this->get(route('frontend.products.index', ['q' => 'A_1']));

        $response->assertSee('Modelo A_1');
        $response->assertDontSee('Modelo AX1');
    }
}
