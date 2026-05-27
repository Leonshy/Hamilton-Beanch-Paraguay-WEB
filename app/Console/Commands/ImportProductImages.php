<?php

namespace App\Console\Commands;

use App\Models\Media;
use App\Models\Product;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class ImportProductImages extends Command
{
    protected $signature   = 'hb:import-product-images';
    protected $description = 'Importa las imágenes de public/images/products al storage y las asocia con los productos';

    // Imagen principal y galería por slug de producto
    private array $map = [
        'cafetera-espresso-retro-black' => [
            'main'    => 'cafetera-retro-black-1.webp',
            'gallery' => [
                'cafetera-retro-black-2.webp',
                'cafetera-retro-black-3.webp',
                'cafetera-retro-black-4.webp',
                'cafetera-retro-black-5.webp',
                'cafetera-retro-black-6.webp',
            ],
        ],
        'cafetera-home-barista-7-in-1' => [
            'main'    => 'cafetera-home-barista.webp',
            'gallery' => [],
        ],
        'tostadora-toaster-silver' => [
            'main'    => 'tostadora-silver.webp',
            'gallery' => [],
        ],
        'pava-electrica-cool-touch' => [
            'main'    => 'pava-cool-touch.webp',
            'gallery' => [],
        ],
        'pava-electrica-double-wall' => [
            'main'    => 'pava-double-wall.webp',
            'gallery' => [],
        ],
        'molinillo-de-cafe-profesional' => [
            'main'    => 'pava-digital.webp',
            'gallery' => [],
        ],
    ];

    public function handle(): void
    {
        $sourceDir = public_path('images/products');
        $adminUser = User::first();

        Storage::disk('public')->makeDirectory('products');

        foreach ($this->map as $productSlug => $config) {
            $product = Product::where('slug', $productSlug)->first();

            if (! $product) {
                $this->warn("Producto no encontrado: {$productSlug}");
                continue;
            }

            // ── Imagen principal ──────────────────────────────────────
            $mainFile = $config['main'];
            $srcPath  = "{$sourceDir}/{$mainFile}";

            if (! file_exists($srcPath)) {
                $this->warn("Imagen no encontrada: {$mainFile}");
                continue;
            }

            $mainMedia = $this->importFile($srcPath, $mainFile, $adminUser?->id);

            $product->update(['media_id' => $mainMedia->id]);
            $this->info("✓ {$product->title} → imagen principal: {$mainFile}");

            // ── Galería ───────────────────────────────────────────────
            $galleryOrder = 1;

            // Incluir la imagen principal también en la galería (posición 0)
            $product->gallery()->syncWithoutDetaching([
                $mainMedia->id => ['order' => 0],
            ]);

            foreach ($config['gallery'] as $galleryFile) {
                $gallSrc = "{$sourceDir}/{$galleryFile}";

                if (! file_exists($gallSrc)) {
                    $this->warn("  Galería no encontrada: {$galleryFile}");
                    continue;
                }

                $gallMedia = $this->importFile($gallSrc, $galleryFile, $adminUser?->id);

                $product->gallery()->syncWithoutDetaching([
                    $gallMedia->id => ['order' => $galleryOrder],
                ]);

                $this->line("  + galería [{$galleryOrder}]: {$galleryFile}");
                $galleryOrder++;
            }
        }

        $this->newLine();
        $this->info('Importación completada.');
    }

    private function importFile(string $srcPath, string $filename, ?int $userId): Media
    {
        $destPath = "products/{$filename}";

        // No recopiar si ya existe en storage
        if (! Storage::disk('public')->exists($destPath)) {
            Storage::disk('public')->put($destPath, file_get_contents($srcPath));
        }

        // Reusar registro existente si ya fue importado antes
        $existing = Media::where('path', $destPath)->where('disk', 'public')->first();
        if ($existing) {
            return $existing;
        }

        $size     = filesize($srcPath);
        $mimeType = mime_content_type($srcPath) ?: 'image/webp';
        $name     = pathinfo($filename, PATHINFO_FILENAME);

        return Media::create([
            'user_id'   => $userId,
            'name'      => $name,
            'file_name' => $filename,
            'mime_type' => $mimeType,
            'path'      => $destPath,
            'disk'      => 'public',
            'size'      => $size,
            'type'      => 'image',
            'alt'       => $name,
            'folder'    => 'products',
        ]);
    }
}
