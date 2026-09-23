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
        // Decodificar una foto de cámara sin redimensionar (ej. 6000×4000)
        // con GD puede pedir varios cientos de MB de golpe solo para el
        // buffer del original — el memory_limit del CLI de Plesk suele
        // quedar en 128M por defecto, insuficiente para esto. Se sube acá,
        // solo para este proceso puntual, sin tocar el memory_limit del
        // sitio web.
        ini_set('memory_limit', '512M');

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
        $failed = [];

        $bar = $this->output->createProgressBar($images->count());
        $bar->start();

        foreach ($images as $i => $media) {
            $before = $media->size ?? 0;

            try {
                $result = $mediaService->optimizeStoredMedia($media, $dryRun);
            } catch (\Throwable $e) {
                $failed[] = "#{$media->id} ({$media->file_name}): " . $e->getMessage();
                $bar->advance();
                continue;
            }

            $bar->advance();

            if ($result === null) {
                continue;
            }

            $totalBefore += $before;
            $totalAfter  += $result['new_size'];
            $optimizedCount++;

            // Liberar los buffers de GD de esta imagen antes de pasar a la
            // siguiente en vez de esperar al recolector de basura de PHP.
            if ($i % 10 === 0) {
                gc_collect_cycles();
            }
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

        if ($failed) {
            $this->warn(count($failed) . ' imagen(es) no se pudieron procesar (quedaron sin tocar, no se perdió nada):');
            foreach ($failed as $f) {
                $this->line("  - {$f}");
            }
        }

        if ($dryRun) {
            $this->comment('Modo dry-run: no se modificó ningún archivo. Correr sin --dry-run para aplicar.');
        } else {
            $this->info('Listo. Puede convenir correr "php artisan view:clear" y revisar el sitio.');
        }

        return self::SUCCESS;
    }
}
