<?php

namespace App\Http\Controllers;

use App\Models\Announcement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;


/**
 * refactor later, put in action classes
 */
class AnnouncementController extends Controller
{
    public function index(Request $request)
    {
        $query = Announcement::query();

        // 👤 Client sees only active
        if (Auth::user()->role === 'client') {
            $query->where('is_active', 1);
        }

        // 🔍 Search 
        if ($request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('title', 'like', "%{$request->search}%")
                  ->orWhere('message', 'like', "%{$request->search}%");
            });
        }

        // 🏷 Type filter
        if ($request->type) {
            $query->where('type', $request->type);
        }

        // ✅ STATUS filter
        if ($request->filled('status')) {
            $query->where('is_active', $request->status);
        }

        // Date from
        if ($request->from) {
            $query->whereDate('created_at', '>=', $request->from);
        }

        // Date to
        if ($request->to) {
            $query->whereDate('created_at', '<=', $request->to);
        }

        $announcements = $query->latest()->paginate(10);

        return view(
            $this->currentRoleView() . '.announcements.index',
            compact('announcements')
        );
    }

    public function show(Announcement $announcement)
    {
        return view(
            $this->currentRoleView() . '.announcements.show',
            compact('announcement')
        );
    }

    public function create()
    {
        return view($this->currentRoleView() . '.announcements.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'message' => 'required|string',
            'type' => 'required|in:promo,update,alert,info',
            'link_page' => 'nullable|in:bookings,services',
            'is_active' => 'nullable|boolean',
            'starts_at' => 'nullable|date',
            'ends_at' => 'nullable|date|after_or_equal:starts_at',
            'cover_image' => 'nullable|image|max:2048',
        ]);

        // handle image upload
        $coverImagePath = null;

        if ($request->hasFile('cover_image')) {
            $coverImagePath = $request->file('cover_image')
                ->store('announcements', 'public');
        }

        $announcement = Announcement::create([
            'title' => $data['title'],
            'message' => $data['message'],
            'type' => $data['type'],
            'link_page' => $data['link_page'] ?? null,
            'is_active' => $data['is_active'] ?? true,
            'starts_at' => $data['starts_at'] ?? null,
            'ends_at' => $data['ends_at'] ?? null,
            'cover_image' => $coverImagePath,
        ]);

        return redirect()
            ->route('announcements.show', $announcement->id)
            ->with('success', 'Announcement created successfully.');
    }


    public function edit(Announcement $announcement)
    {
        return view(
            $this->currentRoleView() . '.announcements.edit',
            compact('announcement')
        );
    }

    public function update(Request $request, Announcement $announcement)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'message' => 'required|string',
            'type' => 'required|in:promo,update,alert,info',
            'link_page' => 'nullable|in:bookings,services',
            'cover_image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'is_active' => 'nullable|boolean',
            'starts_at' => 'nullable|date',
            'ends_at' => 'nullable|date|after_or_equal:starts_at',
        ]);

        // ✅ HANDLE IMAGE UPLOAD
        if ($request->hasFile('cover_image')) {

            // delete old image (optional but recommended)
            if ($announcement->cover_image && Storage::disk('public')->exists($announcement->cover_image)) {
                Storage::disk('public')->delete($announcement->cover_image);
            }

            // store new image
            $path = $request->file('cover_image')->store('announcements', 'public');

            $data['cover_image'] = $path;
        }

        $announcement->update([
            'title' => $data['title'],
            'message' => $data['message'],
            'type' => $data['type'],
            'link_type' => !empty($data['link_url']) ? 'external' : null,
            'link_page' => $data['link_page'] ?? null,
            'cover_image' => $data['cover_image'] ?? $announcement->cover_image,
            'is_active' => $data['is_active'] ?? true,
            'starts_at' => $data['starts_at'] ?? null,
            'ends_at' => $data['ends_at'] ?? null,
        ]);

        return redirect()
            ->route('announcements.show', $announcement->id)
            ->with('success', 'Announcement updated successfully.');
    }

    public function destroy(Announcement $announcement)
    {
        $announcement->delete();

        return to_route('announcements.index')->with('success', 'Announcement deleted successfully.');
    }
}
