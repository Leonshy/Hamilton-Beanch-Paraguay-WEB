<?php

namespace App\Services;

use App\Models\Media;
use enshrined\svgSanitize\Sanitizer;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class MediaService
{
    // Lado más largo permitido, en px. Cubre con margen el uso más grande del
    // sitio (banner 1280×535) sin llegar a pesos de foto de cámara sin recortar.
    private const MAX_DIMENSION = 1600;

    // Calidad WebP (0-100). 82 es el punto donde la pérdida visual es
    // prácticamente imperceptible pero el archivo ya bajó mucho de peso.
    private const WEBP_QUALITY = 82;

    public function upload(UploadedFile $file, string $folder = 'general'): Media
    {
        $originalName = $file->getClientOriginalName();
        $mimeType = $file->getMimeType();
        $size = $file->getSize();
        $type = $this->detectType($mimeType);

        if ($mimeType === 'image/svg+xml') {
            $fileName = Str::uuid() . '.' . $file->getClientOriginalExtension();
            $path = "media/{$folder}/{$fileName}";

            $sanitizer = new Sanitizer();
            $clean = $sanitizer->sanitize(file_get_contents($file->getRealPath()));

            if ($clean === false) {
                throw ValidationException::withMessages([
                    'file' => 'El archivo SVG no pudo procesarse de forma segura.',
                ]);
            }

            Storage::disk('public')->put($path, $clean);
            $size = strlen($clean);
        } else {
            // Para JPG/PNG/WebP: redimensionar si excede MAX_DIMENSION y
            // reconvertir a WebP. Si algo falla (GD sin soporte, formato raro,
            // GIF animado, etc.) se guarda el archivo original tal cual —
            // nunca se rompe una subida por esto.
            $optimized = $this->tryOptimizeImage(file_get_contents($file->getRealPath()), $mimeType);

            if ($optimized !== null) {
                $fileName = Str::uuid() . '.webp';
                $path = "media/{$folder}/{$fileName}";
                Storage::disk('public')->put($path, $optimized['data']);
                $mimeType = $optimized['mime_type'];
                $size = strlen($optimized['data']);
            } else {
                $fileName = Str::uuid() . '.' . $file->getClientOriginalExtension();
                $path = "media/{$folder}/{$fileName}";
                Storage::disk('public')->putFileAs("media/{$folder}", $file, $fileName);
            }
        }

        return Media::create([
            'user_id'   => auth()->id(),
            'name'      => pathinfo($originalName, PATHINFO_FILENAME),
            'file_name' => $fileName,
            'mime_type' => $mimeType,
            'path'      => $path,
            'disk'      => 'public',
            'size'      => $size,
            'type'      => $type,
            'folder'    => $folder,
            'alt'       => pathinfo($originalName, PATHINFO_FILENAME),
        ]);
    }

    public function delete(Media $media): bool
    {
        Storage::disk($media->disk)->delete($media->path);
        return $media->forceDelete();
    }

    /**
     * Reprocesa una imagen YA subida (redimensiona + convierte a WebP) y
     * reemplaza el archivo en storage. Usado por hb:optimize-media para
     * achicar imágenes que se subieron antes de este cambio.
     *
     * Devuelve null si no se pudo optimizar o si el resultado no achica el
     * archivo (nunca reemplaza por algo igual o más pesado). Con $dryRun no
     * escribe nada, solo informa el tamaño que tendría.
     *
     * @return array{new_size: int}|null
     */
    public function optimizeStoredMedia(Media $media, bool $dryRun = false): ?array
    {
        if (!Storage::disk($media->disk)->exists($media->path)) {
            return null;
        }

        $contents = Storage::disk($media->disk)->get($media->path);
        $optimized = $this->tryOptimizeImage($contents, $media->mime_type);

        if ($optimized === null || strlen($optimized['data']) >= strlen($contents)) {
            return null;
        }

        $newSize = strlen($optimized['data']);

        if ($dryRun) {
            return ['new_size' => $newSize];
        }

        $oldPath = $media->path;
        $newFileName = Str::uuid() . '.webp';
        $newPath = dirname($media->path) . '/' . $newFileName;

        Storage::disk($media->disk)->put($newPath, $optimized['data']);

        $media->update([
            'file_name' => $newFileName,
            'path'      => $newPath,
            'mime_type' => $optimized['mime_type'],
            'size'      => $newSize,
        ]);

        if ($oldPath !== $newPath) {
            Storage::disk($media->disk)->delete($oldPath);
        }

        return ['new_size' => $newSize];
    }

    /**
     * Intenta redimensionar (si excede MAX_DIMENSION) y convertir a WebP.
     * Devuelve null si el mime no aplica o si GD no puede procesarlo — en
     * ese caso el llamador debe conservar el archivo original sin tocar.
     *
     * @return array{data: string, mime_type: string}|null
     */
    private function tryOptimizeImage(string $contents, string $mimeType): ?array
    {
        if (!in_array($mimeType, ['image/jpeg', 'image/png', 'image/webp'], true)) {
            return null;
        }

        if (!function_exists('imagewebp') || (gd_info()['WebP Support'] ?? false) !== true) {
            return null;
        }

        // Si ya es WebP y entra dentro de MAX_DIMENSION, no hay nada que
        // ganar: decodificar y volver a codificar WebP puede dar un
        // resultado unos bytes más chico o más grande por ruido de
        // compresión, y eso rompería la idempotencia (cada corrida de
        // hb:optimize-media seguiría "optimizando" sin necesidad).
        if ($mimeType === 'image/webp') {
            $info = @getimagesizefromstring($contents);
            if ($info !== false && $info[0] <= self::MAX_DIMENSION && $info[1] <= self::MAX_DIMENSION) {
                return null;
            }
        }

        $source = @imagecreatefromstring($contents);
        if ($source === false) {
            return null;
        }

        $width  = imagesx($source);
        $height = imagesy($source);

        if ($width > self::MAX_DIMENSION || $height > self::MAX_DIMENSION) {
            $ratio     = min(self::MAX_DIMENSION / $width, self::MAX_DIMENSION / $height);
            $newWidth  = max(1, (int) round($width * $ratio));
            $newHeight = max(1, (int) round($height * $ratio));

            $resized = imagecreatetruecolor($newWidth, $newHeight);
            // Preservar transparencia (PNG con canal alfa)
            imagealphablending($resized, false);
            imagesavealpha($resized, true);
            $transparent = imagecolorallocatealpha($resized, 0, 0, 0, 127);
            imagefilledrectangle($resized, 0, 0, $newWidth, $newHeight, $transparent);

            imagecopyresampled($resized, $source, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);
            imagedestroy($source);
            $source = $resized;
        }

        ob_start();
        $ok = imagewebp($source, null, self::WEBP_QUALITY);
        $data = ob_get_clean();
        imagedestroy($source);

        if (!$ok || $data === false || $data === '') {
            return null;
        }

        return ['data' => $data, 'mime_type' => 'image/webp'];
    }

    private function detectType(string $mimeType): string
    {
        if (str_starts_with($mimeType, 'image/')) return 'image';
        if (str_starts_with($mimeType, 'video/')) return 'video';
        if (in_array($mimeType, [
            'application/pdf',
            'application/msword',
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
        ])) return 'document';
        return 'other';
    }
}
