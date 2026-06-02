<?php

namespace App\Services;

use App\Models\Announcement;
use App\Models\AnnouncementRead;
use App\Models\User;

class AnnouncementReadService
{
    public function record(Announcement $announcement, User $user): AnnouncementRead
    {
        return AnnouncementRead::updateOrCreate(
            [
                'announcement_id' => $announcement->id,
                'user_id' => $user->id,
            ],
            ['read_at' => now()]
        );
    }
}
