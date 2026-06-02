<?php

namespace App\Policies;

use App\Enums\UserRole;
use App\Models\TuitionFee;
use App\Models\User;

class TuitionFeePolicy
{
    public function viewAny(User $user): bool
    {
        return in_array($user->role, [UserRole::Founder, UserRole::Scolarite], true);
    }

    public function view(User $user, TuitionFee $model): bool
    {
        return $this->viewAny($user);
    }

    public function create(User $user): bool
    {
        return in_array($user->role, [UserRole::Founder, UserRole::Scolarite], true);
    }

    public function update(User $user, TuitionFee $model): bool
    {
        return $this->create($user);
    }

    public function delete(User $user, TuitionFee $model): bool
    {
        return $user->role === UserRole::Founder;
    }
}
