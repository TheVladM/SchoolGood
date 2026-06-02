<?php

namespace App\Policies;

use App\Enums\AnnouncementAudience;
use App\Enums\AnnouncementStatus;
use App\Enums\UserRole;
use App\Models\Announcement;
use App\Models\User;

class AnnouncementPolicy
{
    /**
     * Determine if the user can view the model.
     */
    public function view(User $user, Announcement $model): bool
    {
        if ($user->role === UserRole::Parent) {
            if ($model->status !== AnnouncementStatus::Approved) {
                return false;
            }

            return $this->targetsParent($user, $model);
        }

        if (in_array($user->role, [UserRole::Founder, UserRole::Admin, UserRole::Scolarite], true)) {
            if ($model->status === AnnouncementStatus::Approved) {
                return true;
            }

            if ($user->role === UserRole::Scolarite) {
                return $user->is($model->author);
            }

            return true;
        }

        return false;
    }

    /**
     * Determine if the user can create models.
     */
    public function create(User $user): bool
    {
        // Seul les membres de la scolarité peuvent créer des annonces
        return in_array($user->role, [
            UserRole::Founder,
            UserRole::Admin,
            UserRole::Scolarite,
        ]);
    }

    /**
     * Determine if the user can update the model.
     */
    public function update(User $user, Announcement $model): bool
    {
        // Seul l'auteur peut modifier les annonces non approuvées
        if ($model->status !== AnnouncementStatus::Approved) {
            return $user->is($model->author);
        }

        return false;
    }

    /**
     * Determine if the user can approve the model.
     */
    public function approve(User $user, Announcement $model): bool
    {
        return in_array($user->role, [UserRole::Founder, UserRole::Admin], true)
            && $model->status === AnnouncementStatus::PendingApproval;
    }

    /**
     * Determine if the user can reject the model.
     */
    public function reject(User $user, Announcement $model): bool
    {
        return in_array($user->role, [UserRole::Founder, UserRole::Admin], true)
            && $model->status === AnnouncementStatus::PendingApproval;
    }

    /**
     * Determine if the user can delete the model.
     */
    public function delete(User $user, Announcement $model): bool
    {
        // L'auteur peut supprimer les annonces non approuvées
        if ($model->status !== AnnouncementStatus::Approved && $user->is($model->author)) {
            return true;
        }

        return in_array($user->role, [UserRole::Founder, UserRole::Admin], true);
    }

    private function targetsParent(User $user, Announcement $model): bool
    {
        return match ($model->audience) {
            AnnouncementAudience::AllParents => true,
            AnnouncementAudience::Classroom => $user->children()
                ->where('classroom_id', $model->classroom_id)
                ->exists(),
            AnnouncementAudience::Parent => $model->parent_id === $user->id,
            default => false,
        };
    }
}
