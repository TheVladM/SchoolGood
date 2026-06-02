<?php

namespace App\Policies;

use App\Enums\UserRole;
use App\Models\Payment;
use App\Models\User;

class PaymentPolicy
{
    public function viewAny(User $user): bool
    {
        return in_array($user->role, [
            UserRole::Founder,
            UserRole::Scolarite,
            UserRole::Parent,
        ], true);
    }

    public function view(User $user, Payment $model): bool
    {
        if ($user->role === UserRole::Founder || $user->role === UserRole::Scolarite) {
            return true;
        }

        if ($user->role === UserRole::Parent) {
            return $model->student->parent_id === $user->id;
        }

        return false;
    }

    public function create(User $user): bool
    {
        return in_array($user->role, [UserRole::Founder, UserRole::Scolarite], true);
    }

    public function declare(User $user): bool
    {
        return $user->role === UserRole::Parent;
    }

    public function update(User $user, Payment $model): bool
    {
        return in_array($user->role, [UserRole::Founder, UserRole::Scolarite], true);
    }

    public function validate(User $user, Payment $model): bool
    {
        return $user->role === UserRole::Founder;
    }

    public function delete(User $user, Payment $model): bool
    {
        return $user->role === UserRole::Founder;
    }
}
