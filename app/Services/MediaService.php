<?php

namespace App\Services;

use App\Models\Media;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class MediaService
{
    public function upload(UploadedFile $file, string $folder = 'general'): Media
    {
        $originalName = $file->getClientOriginalName();
        $mimeType = $file->getMimeType();
        $size = $file->getSize();
        $type = $this->detectType($mimeType);

        $fileName = Str::uuid() . '.' . $file->getClientOriginalExtension();
        $path = "media/{$folder}/{$fileName}";

        Storage::disk('public')->putFileAs("media/{$folder}", $file, $fileName);

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
