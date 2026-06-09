<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Media;
use App\Services\MediaService;
use Illuminate\Http\Request;

class MediaController extends Controller
{
    public function __construct(private MediaService $mediaService) {}

    public function index()
    {
        $type   = request('type', 'all');
        $folder = request('folder', '');
        $search = request('search', '');

        $query = Media::latest();

        if ($type !== 'all') {
            $query->where('type', $type);
        }
        if ($folder) {
            $query->where('folder', $folder);
        }
        if ($search) {
            $query->where(fn($q) => $q->where('name', 'like', "%{$search}%")
                ->orWhere('file_name', 'like', "%{$search}%"));
        }

        $media   = $query->paginate(24);
        $folders = Media::select('folder')->distinct()->pluck('folder')->filter()->values();

        return view('admin.media.index', compact('media', 'type', 'folder', 'search', 'folders'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'file'   => 'required|file|max:65536|mimes:jpg,jpeg,png,gif,webp,svg,pdf,doc,docx,xls,xlsx,zip',
            'folder' => 'nullable|string|max:100|regex:/^[a-zA-Z0-9_\-\/]+$/',
        ]);

        $media = $this->mediaService->upload($request->file('file'), $request->folder ?? 'general');

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'media'   => [
                    'id'   => $media->id,
                    'name' => $media->name,
                    'url'  => $media->url,
                    'type' => $media->type,
                    'size' => $media->human_size,
                ],
            ]);
        }

        return back()->with('success', 'Archivo subido correctamente.');
    }

    public function update(Request $request, Media $media)
    {
        $request->validate([
            'alt'     => 'nullable|string|max:255',
            'title'   => 'nullable|string|max:255',
            'caption' => 'nullable|string|max:500',
            'folder'  => 'nullable|string|max:100',
        ]);

        $media->update($request->only('alt', 'title', 'caption', 'folder'));
        return back()->with('success', 'Archivo actualizado.');
    }

    public function destroy(Media $media)
    {
        $this->mediaService->delete($media);
        return back()->with('success', 'Archivo eliminado.');
    }

    public function picker(Request $request)
    {
        $type   = $request->input('type', 'image');
        $search = $request->input('search', '');
        $page   = max(1, (int) $request->input('page', 1));
        $perPage = 60;

        $query = Media::where('type', $type)->latest();

        if ($search) {
            $query->where(fn($q) => $q->where('name', 'like', "%{$search}%")
                ->orWhere('file_name', 'like', "%{$search}%"));
        }

        $paginated = $query->paginate($perPage, ['*'], 'page', $page);

        return response()->json([
            'items'    => $paginated->map(fn($m) => [
                'id'    => $m->id,
                'title' => $m->name,
                'value' => $m->url,
                'thumb' => $m->type === 'image' ? $m->url : null,
            ]),
            'has_more' => $paginated->hasMorePages(),
            'next_page' => $paginated->hasMorePages() ? $page + 1 : null,
            'total'    => $paginated->total(),
        ]);
    }
}
