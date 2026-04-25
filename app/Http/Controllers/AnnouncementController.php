<?php

namespace App\Http\Controllers;

use App\Models\Announcement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AnnouncementController extends Controller
{
    public function index(Request $request)
    {
        $query = Announcement::query();

        if (Auth::user()->role === 'client') {
            $query->where('is_active', 1);
        }

        if ($request->search) {
            $query->where('title', 'like', "%{$request->search}%")
                ->orWhere('message', 'like', "%{$request->search}%");
        }

        if ($request->type) {
            $query->where('type', $request->type);
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
            'link_url' => 'nullable|url',
            'is_active' => 'nullable|boolean',
            'starts_at' => 'nullable|date',
            'ends_at' => 'nullable|date|after_or_equal:starts_at',
        ]);

        $announcement = Announcement::create([
            'title' => $data['title'],
            'message' => $data['message'],
            'type' => $data['type'],
            'link_type' => !empty($data['link_url']) ? 'external' : null,
            'link_id' => null,
            'link_url' => $data['link_url'] ?? null,
            'is_active' => $data['is_active'] ?? true,
            'starts_at' => $data['starts_at'] ?? null,
            'ends_at' => $data['ends_at'] ?? null,
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
            'link_url' => 'nullable|url',
            'is_active' => 'nullable|boolean',
            'starts_at' => 'nullable|date',
            'ends_at' => 'nullable|date|after_or_equal:starts_at',
        ]);

        $announcement->update([
            'title' => $data['title'],
            'message' => $data['message'],
            'type' => $data['type'],
            'link_type' => !empty($data['link_url']) ? 'external' : null,
            'link_id' => null,
            'link_url' => $data['link_url'] ?? null,
            'is_active' => $data['is_active'] ?? true,
            'starts_at' => $data['starts_at'] ?? null,
            'ends_at' => $data['ends_at'] ?? null,
        ]);

        return redirect()
            ->route('announcements.show', $announcement->id)
            ->with('success', 'Announcement updated successfully.');
    }
}
