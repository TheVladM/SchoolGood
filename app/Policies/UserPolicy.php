<?php

namespace App\Policies;

use App\Enums\UserRole;
use App\Models\User;

class UserPolicy
{
    /**
     * Determine if the user can view the model.
     */
    public function view(User $user, User $model): bool
    {
        // Fondateur peut voir tous les utilisateurs
        if ($user->role === UserRole::Founder) {
            return true;
        }

        // Admin peut voir tous les utilisateurs
        if ($user->role === UserRole::Admin) {
            return true;
        }

        // Les autres ne peuvent se voir que eux-mêmes
        return $user->is($model);
    }

    /**
     * Determine if the user can create models.
     */
    public function create(User $user): bool
    {
        // Seul le fondateur et l'admin peuvent créer des utilisateurs
        return in_array($user->role, [UserRole::Founder, UserRole::Admin]);
    }

    /**
     * Determine if the user can update the model.
     */
    public function update(User $user, User $model): bool
    {
        // Le fondateur ne peut pas être modifié
        if ($model->role === UserRole::Founder && !$user->is($model)) {
            return false;
        }

        // Fondateur peut modifier tous les utilisateurs (sauf les règles ci-dessus)
        if ($user->role === UserRole::Founder) {
            return true;
        }

        // Les utilisateurs ne peuvent se modifier que eux-mêmes
        return $user->is($model);
    }

    /**
     * Determine if the user can delete the model.
     */
    public function delete(User $user, User $model): bool
    {
        // Le fondateur ne peut pas être supprimé
        if ($model->role === UserRole::Founder) {
            return false;
        }

        // Seul le fondateur peut supprimer des utilisateurs
        if ($user->role === UserRole::Founder) {
            return true;
        }

        return false;
    }

    /**
     * Determine if the user can restore the model.
     */
    public function restore(User $user, User $model): bool
    {
        return $user->role === UserRole::Founder;
    }

    /**
     * Determine if the user can permanently delete the model.
     */
    public function forceDelete(User $user, User $model): bool
    {
        return $user->role === UserRole::Founder;
    }
}
