<?php

namespace Tests\Feature;

use App\Models\Banner;
use App\Models\Category;
use App\Models\Media;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class HomeTest extends TestCase
{
    use RefreshDatabase;

    private function media(): Media
    {
        return Media::create([
            'name' => 'banner', 'file_name' => 'b.jpg', 'mime_type' => 'image/jpeg',
            'path' => 'media/general/b.jpg', 'disk' => 'public', 'size' => 100, 'type' => 'image', 'folder' => 'general',
        ]);
    }

    public function test_banner_with_link_renders_as_real_anchor_not_onclick(): void
    {
        Banner::create([
            'media_id' => $this->media()->id,
            'link_url' => 'https://ejemplo.com.py/promo?x=1&y=2',
            'position' => 'home', 'order' => 1, 'is_active' => true,
        ]);

        $response = $this->get('/');

        $response->assertSee('<a href="https://ejemplo.com.py/promo?x=1&amp;y=2"', false);
        $response->assertDontSee('onclick="window.location', false);
    }

    public function test_banner_link_url_with_quote_does_not_break_the_page(): void
    {
        Banner::create([
            'media_id' => $this->media()->id,
            'link_url' => "https://ejemplo.com.py/x'y",
            'position' => 'home', 'order' => 1, 'is_active' => true,
        ]);

        // Antes del fix esto rompía el atributo href al imprimirse dentro
        // de un onclick con comillas simples sin escapar para JS.
        $this->get('/')->assertOk();
    }

    public function test_featured_products_images_use_lazy_loading(): void
    {
        $category = Category::create(['type' => 'product', 'name' => 'Cat', 'slug' => 'cat', 'is_active' => true, 'order' => 1]);
        Product::create([
            'title' => 'Destacado', 'slug' => 'destacado', 'status' => 'published', 'order' => 1,
            'category_id' => $category->id, 'is_featured' => true,
        ]);

        $this->get('/')->assertSee('loading="lazy"', false);
    }
}
