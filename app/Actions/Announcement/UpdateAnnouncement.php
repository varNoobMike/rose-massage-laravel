<?php

namespace App\Actions\Announcement;

use App\Models\Announcement;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class UpdateAnnouncement
{
    public function execute(
        Announcement $announcement,
        array $data
    ): Announcement {

        return DB::transaction(function () use ($announcement, $data) {

            // existing image
            $coverImage = $announcement->cover_image;

            /**
             * handle image replacement
             */
            if (($data['cover_image'] ?? null) instanceof UploadedFile) {

                // delete old image
                if ($announcement->cover_image) {
                    Storage::disk('public')
                        ->delete($announcement->cover_image);
                }

                // store new image
                $coverImage = $data['cover_image']
                    ->store('announcements', 'public');
            }

            // update announcement
            $announcement->update([
                'title' => $data['title'],
                'message' => $data['message'],
                'type' => $data['type'],
                'link_type' => !empty($data['link_page'])
                    ? 'internal'
                    : null,
                'link_page' => $data['link_page'] ?? null,
                'cover_image' => $coverImage,
                'is_active' => $data['is_active'] ?? true,
                'starts_at' => $data['starts_at'] ?? null,
                'ends_at' => $data['ends_at'] ?? null,
            ]);

            return $announcement;
        });
    }
}
