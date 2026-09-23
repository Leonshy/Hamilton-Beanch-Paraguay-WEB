<?php

namespace App\Console\Commands;

use App\Models\Media;
use App\Services\MediaService;
use Illuminate\Console\Command;

class OptimizeMedia extends Command
{
    protected $signature = 'hb:optimize-media {--dry-run : Solo mostrar el ahorro estimado, sin modificar nada}';

    protected $description = 'Redimensiona y convierte a WebP las imágenes ya subidas a la biblioteca de medios (SVG queda sin tocar)';

    public function handle(MediaService $mediaService): int
    {
        $dryRun = (bool) $this->option('dry-run');

        $images = Media::where('type', 'image')
            ->where('mime_type', '!=', 'image/svg+xml')
            ->get();

        if ($images->isEmpty()) {
            $this->info('No hay imágenes para optimizar.');
            return self::SUCCESS;
        }

        $this->info(($dryRun ? '[dry-run] ' : '') . "Procesando {$images->count()} imágenes...");

        $totalBefore = 0;
        $totalAfter  = 0;
        $optimizedCount = 0;

        $bar = $this->output->createProgressBar($images->count());
        $bar->start();

        foreach ($images as $media) {
            $before = $media->size ?? 0;
            $result = $mediaService->optimizeStoredMedia($media, $dryRun);
            $bar->advance();

            if ($result === null) {
                continue;
            }

            $totalBefore += $before;
            $totalAfter  += $result['new_size'];
            $optimizedCount++;
        }

        $bar->finish();
        $this->newLine(2);

        $savedMb  = ($totalBefore - $totalAfter) / 1048576;
        $beforeMb = $totalBefore / 1048576;
        $afterMb  = $totalAfter / 1048576;

        $this->table(
            ['Imágenes optimizadas', 'Antes', 'Después', 'Ahorro'],
            [[$optimizedCount, sprintf('%.1f MB', $beforeMb), sprintf('%.1f MB', $afterMb), sprintf('%.1f MB', $savedMb)]]
        );

        if ($dryRun) {
            $this->comment('Modo dry-run: no se modificó ningún archivo. Correr sin --dry-run para aplicar.');
        } else {
            $this->info('Listo. Puede convenir correr "php artisan view:clear" y revisar el sitio.');
        }

        return self::SUCCESS;
    }
}
