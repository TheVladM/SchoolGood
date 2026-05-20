<?php

namespace App\Policies;

use App\Enums\UserRole;
use App\Models\Payment;
use App\Models\User;

class PaymentPolicy
{
    /**
     * Determine if the user can view the model.
     */
    public function view(User $user, Payment $model): bool
    {
        // Fondateur peut voir tous les paiements
        if ($user->role === UserRole::Founder) {
            return true;
        }

        // Admin et Scolarite peuvent voir tous les paiements
        if (in_array($user->role, [UserRole::Admin, UserRole::Scolarite])) {
            return true;
        }

        // Les parents ne peuvent voir que les paiements de leurs enfants
        if ($user->role === UserRole::Parent) {
            return $model->student->parent_id === $user->id;
        }

        return false;
    }

    /**
     * Determine if the user can create models.
     */
    public function create(User $user): bool
    {
        // Seul Fondateur, Admin et Scolarite peuvent créer les paiements
        return in_array($user->role, [
            UserRole::Founder,
            UserRole::Admin,
            UserRole::Scolarite,
        ]);
    }

    /**
     * Determine if the user can update the model.
     */
    public function update(User $user, Payment $model): bool
    {
        // Seul Fondateur, Admin et Scolarite peuvent modifier les paiements
        return in_array($user->role, [
            UserRole::Founder,
            UserRole::Admin,
            UserRole::Scolarite,
        ]);
    }

    /**
     * Determine if the user can validate (approve) a payment.
     */
    public function validate(User $user, Payment $model): bool
    {
        // Seul le Fondateur et Admin peuvent valider les paiements
        return in_array($user->role, [UserRole::Founder, UserRole::Admin]);
    }

    /**
     * Determine if the user can delete the model.
     */
    public function delete(User $user, Payment $model): bool
    {
        // Seul le Fondateur peut supprimer les paiements
        return $user->role === UserRole::Founder;
    }
}
