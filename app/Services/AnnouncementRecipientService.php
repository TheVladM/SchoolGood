<?php

namespace App\Services;

use App\Enums\AnnouncementAudience;
use App\Enums\AnnouncementStatus;
use App\Enums\UserRole;
use App\Models\Announcement;
use App\Models\Student;
use App\Models\User;
use Illuminate\Support\Collection;

class AnnouncementRecipientService
{
    /**
     * @return Collection<int, User>
     */
    public function parents(Announcement $announcement): Collection
    {
        if ($announcement->status !== AnnouncementStatus::Approved) {
            return collect();
        }

        return match ($announcement->audience) {
            AnnouncementAudience::AllParents => User::query()
                ->where('role', UserRole::Parent->value)
                ->get(),
            AnnouncementAudience::Classroom => User::query()
                ->where('role', UserRole::Parent->value)
                ->whereIn('id', Student::query()
                    ->where('classroom_id', $announcement->classroom_id)
                    ->pluck('parent_id'))
                ->get(),
            AnnouncementAudience::Parent => collect(
                $announcement->parent_id
                    ? [User::find($announcement->parent_id)]
                    : []
            )->filter(),
            default => collect(),
        };
    }
}
