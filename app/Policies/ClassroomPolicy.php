<?php

namespace App\Policies;

use App\Enums\UserRole;
use App\Models\Classroom;
use App\Models\User;

class ClassroomPolicy
{
    /**
     * Determine if the user can view the model.
     */
    public function view(User $user, Classroom $model): bool
    {
        // Tous les utilisateurs authentifiés peuvent voir les classes
        return true;
    }

    /**
     * Determine if the user can create models.
     */
    public function create(User $user): bool
    {
        // Seul Admin, Fondateur et Scolarite peuvent créer les classes
        return in_array($user->role, [
            UserRole::Founder,
            UserRole::Admin,
            UserRole::Scolarite,
        ]);
    }

    /**
     * Determine if the user can update the model.
     */
    public function update(User $user, Classroom $model): bool
    {
        // Seul Admin, Fondateur et Scolarite peuvent modifier les classes
        return in_array($user->role, [
            UserRole::Founder,
            UserRole::Admin,
            UserRole::Scolarite,
        ]);
    }

    /**
     * Determine if the user can delete the model.
     */
    public function delete(User $user, Classroom $model): bool
    {
        // Seul Fondateur et Admin peuvent supprimer les classes
        return in_array($user->role, [UserRole::Founder, UserRole::Admin]);
    }
}
