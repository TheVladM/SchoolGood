<?php

namespace App\Policies;

use App\Enums\UserRole;
use App\Models\Book;
use App\Models\User;

class BookPolicy
{
    /**
     * Determine if the user can view the model.
     */
    public function view(User $user, Book $model): bool
    {
        // Tous les utilisateurs authentifiés peuvent voir les livres
        return true;
    }

    /**
     * Determine if the user can create models.
     */
    public function create(User $user): bool
    {
        return in_array($user->role, [UserRole::Founder, UserRole::Admin], true);
    }

    /**
     * Determine if the user can update the model.
     */
    public function update(User $user, Book $model): bool
    {
        return in_array($user->role, [UserRole::Founder, UserRole::Admin], true);
    }

    /**
     * Determine if the user can delete the model.
     */
    public function delete(User $user, Book $model): bool
    {
        // Seul Fondateur et Admin peuvent supprimer les livres
        return in_array($user->role, [UserRole::Founder, UserRole::Admin]);
    }
}
