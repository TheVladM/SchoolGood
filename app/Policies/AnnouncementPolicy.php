<?php

namespace App\Policies;

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
        // Les annonces approuvées sont visibles à tous
        if ($model->status === AnnouncementStatus::Approved) {
            return true;
        }

        // L'auteur et le fondateur peuvent voir les annonces non approuvées
        if ($user->is($model->author) || $user->role === UserRole::Founder) {
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
        // Seul le fondateur peut approuver les annonces
        return $user->role === UserRole::Founder;
    }

    /**
     * Determine if the user can reject the model.
     */
    public function reject(User $user, Announcement $model): bool
    {
        // Seul le fondateur peut rejeter les annonces
        return $user->role === UserRole::Founder;
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

        // Le fondateur peut supprimer toutes les annonces
        return $user->role === UserRole::Founder;
    }
}
