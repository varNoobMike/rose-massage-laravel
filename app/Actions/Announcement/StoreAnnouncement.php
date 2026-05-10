<?php

namespace App\Actions\Announcement;

use App\Models\Announcement;
use App\Models\User;
use App\Notifications\NewAnnouncementNotification;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;

class StoreAnnouncement
{
    /**
     * Create announcement
     */
    public function execute(array $data): Announcement
    {
        return DB::transaction(function () use ($data) {

            // default image
            $coverImagePath = null;

            // upload image if exists
            if (($data['cover_image'] ?? null) instanceof UploadedFile) {
                $coverImagePath = $data['cover_image']
                    ->store('announcements', 'public');
            }

            // create announcement
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

            // get all clients
            $recipients = User::where('role', User::ROLE_CLIENT)->get();

            // send notification
            Notification::send(
                $recipients,
                new NewAnnouncementNotification($announcement)
            );

            return $announcement;
        });
    }
}
