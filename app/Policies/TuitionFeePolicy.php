<?php

namespace App\Policies;

use App\Enums\UserRole;
use App\Models\TuitionFee;
use App\Models\User;

class TuitionFeePolicy
{
    /**
     * Determine if the user can view the model.
     */
    public function view(User $user, TuitionFee $model): bool
    {
        // Fondateur, Admin et Scolarité peuvent voir tous les frais
        return in_array($user->role, [
            UserRole::Founder,
            UserRole::Admin,
            UserRole::Scolarite,
        ]);
    }

    /**
     * Determine if the user can create models.
     */
    public function create(User $user): bool
    {
        // Seul le Fondateur peut créer/modifier les tarifs de frais
        return $user->role === UserRole::Founder;
    }

    /**
     * Determine if the user can update the model.
     */
    public function update(User $user, TuitionFee $model): bool
    {
        // Seul le Fondateur peut modifier les tarifs
        return $user->role === UserRole::Founder;
    }

    /**
     * Determine if the user can delete the model.
     */
    public function delete(User $user, TuitionFee $model): bool
    {
        // Seul le Fondateur peut supprimer les tarifs
        return $user->role === UserRole::Founder;
    }
}
