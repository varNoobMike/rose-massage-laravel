<?php

namespace App\Http\Controllers;

use App\Actions\Announcement\DestroyAnnouncement;
use App\Actions\Announcement\GetFilteredAnnouncements;
use App\Actions\Announcement\StoreAnnouncement;
use App\Actions\Announcement\UpdateAnnouncement;
use App\Models\Announcement;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class AnnouncementController extends Controller
{
    public function index(Request $request, GetFilteredAnnouncements $action)
    {
        $filters = $request->only([
            'search',
            'type',
            'status',
            'date_from',
            'date_to',
        ]);

        // fetch filtered announcements
        $announcements = $action->execute($filters, Auth::user());

        // basic filters state
        $hasBasicFilters =
            !empty($filters['search']) ||
            !empty($filters['type']) ||
            !empty($filters['status']) ||
            !empty($filters['date_from']) ||
            !empty($filters['date_to']);

        // global filters state
        $hasFilters = $hasBasicFilters;

        return view(
            $this->currentRoleView() . '.announcements.index',
            compact('announcements', 'hasFilters', 'filters')
        );
    }

    public function show(Announcement $announcement)
    {
        abort_if(
            Auth::user()->role === User::ROLE_CLIENT &&
                $announcement->is_active == 0,
            404
        );

        return view(
            $this->currentRoleView() . '.announcements.show',
            compact('announcement')
        );
    }

    public function create()
    {
        return view($this->currentRoleView() . '.announcements.create');
    }

    public function store(Request $request, StoreAnnouncement $action)
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

        $announcement = $action->execute($data);

        return redirect()
            ->route('announcements.show', $announcement->id)
            ->with(
                'success',
                'Announcement created successfully.'
            );
    }

    public function edit(Announcement $announcement)
    {
        return view(
            $this->currentRoleView() . '.announcements.edit',
            compact('announcement')
        );
    }

    public function update(
        Request $request,
        Announcement $announcement,
        UpdateAnnouncement $action
    ) {

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'message' => 'required|string',
            'type' => 'required|in:promo,update,alert,info',
            'link_page' => 'nullable|in:bookings,services',
            'cover_image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'is_active' => 'nullable|boolean',
            'starts_at' => 'nullable|date',
            'ends_at' => 'nullable|date|after_or_equal:starts_at',
        ]);

        $announcement = $action->execute(
            $announcement,
            $validated
        );

        return to_route('announcements.show', $announcement->id)
            ->with('success', 'Announcement updated successfully.');
    }

    public function destroy(Announcement $announcement, DestroyAnnouncement $action)
    {
        $action->execute($announcement);

        return to_route('announcements.index')
            ->with('success', 'Announcement deleted successfully.');
    }
}
