<?php

namespace App\Actions\Announcement;

use App\Models\Announcement;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class DestroyAnnouncement
{
    public function execute(
        Announcement $announcement
    ): void {

        DB::transaction(function () use ($announcement) {

            // delete cover image
            if ($announcement->cover_image) {
                Storage::disk('public')
                    ->delete($announcement->cover_image);
            }

            // delete announcement
            $announcement->delete();
        });
    }
}
