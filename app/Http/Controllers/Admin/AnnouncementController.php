<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Announcement;
use App\Traits\HandlesOrder;
use Illuminate\Http\Request;

class AnnouncementController extends Controller
{
    use HandlesOrder;
    public function index()
    {
        $announcements = Announcement::orderBy('order')->get();
        return view('admin.announcements.index', compact('announcements'));
    }

    public function create()
    {
        $nextOrder = $this->nextOrder(Announcement::class);
        return view('admin.announcements.form', compact('nextOrder'));
    }

    public function store(Request $request)
    {
        $data = $this->validateAnnouncement($request);
        $data['is_active'] = $request->boolean('is_active');
        $data['order'] = $data['order'] ?? $this->nextOrder(Announcement::class);
        $this->shiftOrderUp(Announcement::class, (int) $data['order']);
        Announcement::create($data);
        return redirect()->route('admin.announcements.index')->with('success', 'Anuncio creado correctamente.');
    }

    public function edit(Announcement $announcement)
    {
        return view('admin.announcements.form', compact('announcement'));
    }

    public function update(Request $request, Announcement $announcement)
    {
        $data = $this->validateAnnouncement($request);
        $data['is_active'] = $request->boolean('is_active');
        $this->shiftOrderUp(Announcement::class, (int) $data['order'], $announcement->id);
        $announcement->update($data);
        return redirect()->route('admin.announcements.index')->with('success', 'Anuncio actualizado.');
    }

    public function destroy(Announcement $announcement)
    {
        $announcement->delete();
        return redirect()->route('admin.announcements.index')->with('success', 'Anuncio eliminado.');
    }

    private function validateAnnouncement(Request $request): array
    {
        return $request->validate([
            'text'  => 'required|string|max:255',
            'order' => 'nullable|integer|min:0',
        ]);
    }
}
