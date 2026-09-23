<?php

namespace Tests\Feature;

use App\Models\Media;
use App\Models\User;
use App\Services\MediaService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class MediaOptimizationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Storage::fake('public');
        $this->actingAs(User::factory()->create(['is_active' => true]));
    }

    private function makeImage(int $width, int $height, string $format = 'png'): string
    {
        $path = tempnam(sys_get_temp_dir(), 'hbimg') . ".{$format}";
        $img = imagecreatetruecolor($width, $height);
        $color = imagecolorallocate($img, random_int(0, 255), random_int(0, 255), random_int(0, 255));
        imagefill($img, 0, 0, $color);
        // ruido para que no sea trivialmente comprimible
        for ($i = 0; $i < 500; $i++) {
            $c = imagecolorallocate($img, random_int(0, 255), random_int(0, 255), random_int(0, 255));
            imagesetpixel($img, random_int(0, $width - 1), random_int(0, $height - 1), $c);
        }
        $format === 'jpeg' ? imagejpeg($img, $path, 90) : imagepng($img, $path);
        imagedestroy($img);

        return $path;
    }

    public function test_oversized_upload_is_resized_and_converted_to_webp(): void
    {
        $path = $this->makeImage(2400, 1600);
        $file = new UploadedFile($path, 'big.png', 'image/png', null, true);

        $media = app(MediaService::class)->upload($file, 'general');

        $this->assertEquals('image/webp', $media->mime_type);
        $this->assertStringEndsWith('.webp', $media->path);

        $info = getimagesize(Storage::disk('public')->path($media->path));
        $this->assertLessThanOrEqual(1600, $info[0]);
        $this->assertLessThanOrEqual(1600, $info[1]);

        @unlink($path);
    }

    public function test_small_jpeg_is_converted_to_webp_without_resizing(): void
    {
        $path = $this->makeImage(200, 200, 'jpeg');
        $file = new UploadedFile($path, 'small.jpg', 'image/jpeg', null, true);

        $media = app(MediaService::class)->upload($file, 'general');

        $this->assertEquals('image/webp', $media->mime_type);
        $info = getimagesize(Storage::disk('public')->path($media->path));
        $this->assertEquals(200, $info[0]);
        $this->assertEquals(200, $info[1]);

        @unlink($path);
    }

    public function test_svg_upload_is_never_converted(): void
    {
        $path = tempnam(sys_get_temp_dir(), 'hbsvg') . '.svg';
        file_put_contents($path, '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 10 10"><circle cx="5" cy="5" r="4"/></svg>');
        $file = new UploadedFile($path, 'icon.svg', 'image/svg+xml', null, true);

        $media = app(MediaService::class)->upload($file, 'general');

        $this->assertEquals('image/svg+xml', $media->mime_type);
        $this->assertStringEndsWith('.svg', $media->path);

        @unlink($path);
    }

    public function test_gif_upload_is_never_converted_to_preserve_possible_animation(): void
    {
        $path = tempnam(sys_get_temp_dir(), 'hbgif') . '.gif';
        $img = imagecreatetruecolor(50, 50);
        imagegif($img, $path);
        imagedestroy($img);
        $file = new UploadedFile($path, 'anim.gif', 'image/gif', null, true);

        $media = app(MediaService::class)->upload($file, 'general');

        $this->assertEquals('image/gif', $media->mime_type);
        $this->assertStringEndsWith('.gif', $media->path);

        @unlink($path);
    }

    public function test_optimize_stored_media_dry_run_does_not_modify_anything(): void
    {
        $path = $this->makeImage(2400, 1600);
        Storage::disk('public')->put('media/general/legacy.png', file_get_contents($path));
        $media = Media::create([
            'name' => 'legacy', 'file_name' => 'legacy.png', 'mime_type' => 'image/png',
            'path' => 'media/general/legacy.png', 'disk' => 'public',
            'size' => filesize($path), 'type' => 'image', 'folder' => 'general',
        ]);

        $result = app(MediaService::class)->optimizeStoredMedia($media, dryRun: true);

        $this->assertNotNull($result);
        $media->refresh();
        $this->assertEquals('media/general/legacy.png', $media->path);
        $this->assertEquals('image/png', $media->mime_type);
        $this->assertTrue(Storage::disk('public')->exists('media/general/legacy.png'));

        @unlink($path);
    }

    public function test_optimize_stored_media_replaces_file_and_is_idempotent(): void
    {
        $path = $this->makeImage(2400, 1600);
        Storage::disk('public')->put('media/general/legacy.png', file_get_contents($path));
        $media = Media::create([
            'name' => 'legacy', 'file_name' => 'legacy.png', 'mime_type' => 'image/png',
            'path' => 'media/general/legacy.png', 'disk' => 'public',
            'size' => filesize($path), 'type' => 'image', 'folder' => 'general',
        ]);
        $originalSize = $media->size;

        $service = app(MediaService::class);
        $first = $service->optimizeStoredMedia($media);

        $this->assertNotNull($first);
        $media->refresh();
        $this->assertEquals('image/webp', $media->mime_type);
        $this->assertLessThan($originalSize, $media->size);
        $this->assertFalse(Storage::disk('public')->exists('media/general/legacy.png'));
        $this->assertTrue(Storage::disk('public')->exists($media->path));

        // Segunda corrida sobre el mismo registro ya optimizado: no debe
        // volver a reemplazar el archivo (evita re-encode innecesario).
        $second = $service->optimizeStoredMedia($media);
        $this->assertNull($second);

        @unlink($path);
    }
}
