<?php

namespace App\Policies;

use App\Enums\UserRole;
use App\Models\Room;
use App\Models\User;

class RoomPolicy
{
    public function viewAny(User $user): bool
    {
        return in_array($user->role, [UserRole::Founder, UserRole::Admin, UserRole::Scolarite], true);
    }

    public function view(User $user, Room $model): bool
    {
        return $this->viewAny($user);
    }

    public function create(User $user): bool
    {
        return in_array($user->role, [UserRole::Founder, UserRole::Admin], true);
    }

    public function update(User $user, Room $model): bool
    {
        return $this->create($user);
    }

    public function delete(User $user, Room $model): bool
    {
        return $user->role === UserRole::Founder;
    }
}
