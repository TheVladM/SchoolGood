<?php

namespace App\Policies;

use App\Enums\UserRole;
use App\Models\BookLoan;
use App\Models\User;

class BookLoanPolicy
{
    /**
     * Determine if the user can view the model.
     */
    public function view(User $user, BookLoan $model): bool
    {
        // Fondateur et Admin peuvent voir tous les emprunts
        if (in_array($user->role, [UserRole::Founder, UserRole::Admin, UserRole::Scolarite])) {
            return true;
        }

        // L'emprunteur et le parent de l'étudiant peuvent voir l'emprunt
        if ($user->is($model->user)) {
            return true;
        }

        if ($model->student && $model->student->parent_id === $user->id) {
            return true;
        }

        return false;
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
    public function update(User $user, BookLoan $model): bool
    {
        return in_array($user->role, [UserRole::Founder, UserRole::Admin, UserRole::Scolarite], true);
    }

    /**
     * Determine if the user can process the return.
     */
    public function return(User $user, BookLoan $model): bool
    {
        return in_array($user->role, [UserRole::Founder, UserRole::Admin, UserRole::Scolarite], true);
    }

    /**
     * Determine if the user can delete the model.
     */
    public function delete(User $user, BookLoan $model): bool
    {
        // Seul Fondateur peut supprimer les emprunts
        return $user->role === UserRole::Founder;
    }
}
