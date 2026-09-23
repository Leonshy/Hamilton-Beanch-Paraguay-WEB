<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use App\Models\Category;
use App\Models\Media;
use App\Models\Page;
use App\Models\Product;
use App\Models\SalePoint;
use App\Services\MediaService;
use App\Traits\EscapesLikeSearch;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MediaController extends Controller
{
    use EscapesLikeSearch;

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
            $like = '%' . $this->escapeLike($search) . '%';
            $query->where(fn($q) => $q->whereRaw("name LIKE ? ESCAPE '\\'", [$like])
                ->orWhereRaw("file_name LIKE ? ESCAPE '\\'", [$like]));
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

    public function destroy(Media $media)
    {
        if ($this->isInUse($media)) {
            return back()->with('error', 'No se puede eliminar: el archivo está en uso (producto, categoría, banner, página o punto de venta).');
        }

        $this->mediaService->delete($media);
        return back()->with('success', 'Archivo eliminado.');
    }

    private function isInUse(Media $media): bool
    {
        return Product::where('media_id', $media->id)->exists()
            || DB::table('product_media')->where('media_id', $media->id)->exists()
            || Category::where('media_id', $media->id)->exists()
            || Page::where('media_id', $media->id)->exists()
            || Banner::where('media_id', $media->id)->exists()
            || SalePoint::where('media_id', $media->id)->exists();
    }

    public function picker(Request $request)
    {
        $type   = $request->input('type', 'image');
        $search = $request->input('search', '');
        $page   = max(1, (int) $request->input('page', 1));
        $perPage = 60;

        $query = Media::where('type', $type)->latest();

        if ($search) {
            $like = '%' . $this->escapeLike($search) . '%';
            $query->where(fn($q) => $q->whereRaw("name LIKE ? ESCAPE '\\'", [$like])
                ->orWhereRaw("file_name LIKE ? ESCAPE '\\'", [$like]));
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
