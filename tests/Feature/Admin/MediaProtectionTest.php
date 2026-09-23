<?php

namespace Tests\Feature\Admin;

use App\Models\Category;
use App\Models\Media;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class MediaProtectionTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Storage::fake('public');
        Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);
        $user = User::factory()->create(['is_active' => true]);
        $user->assignRole('admin');
        $this->actingAs($user);
    }

    private function media(): Media
    {
        $path = 'media/general/test.png';
        Storage::disk('public')->put($path, 'contenido');

        return Media::create([
            'name' => 'test', 'file_name' => 'test.png', 'mime_type' => 'image/png',
            'path' => $path, 'disk' => 'public', 'size' => 9, 'type' => 'image', 'folder' => 'general',
        ]);
    }

    public function test_cannot_delete_media_used_as_category_image(): void
    {
        $media = $this->media();
        Category::create(['type' => 'product', 'name' => 'Cat', 'slug' => 'cat', 'is_active' => true, 'order' => 1, 'media_id' => $media->id]);

        $response = $this->delete(route('admin.media.destroy', $media));

        $response->assertSessionHas('error');
        $this->assertDatabaseHas('media', ['id' => $media->id]);
        $this->assertTrue(Storage::disk('public')->exists($media->path));
    }

    public function test_can_delete_media_not_referenced_anywhere(): void
    {
        $media = $this->media();

        $response = $this->delete(route('admin.media.destroy', $media));

        $response->assertSessionHas('success');
        $this->assertDatabaseMissing('media', ['id' => $media->id]);
    }
}
