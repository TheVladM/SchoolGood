<?php

namespace App\Policies;

use App\Enums\UserRole;
use App\Models\TimetableEntry;
use App\Models\User;

class TimetableEntryPolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, TimetableEntry $model): bool
    {
        return true;
    }

    public function create(User $user): bool
    {
        return in_array($user->role, [UserRole::Founder, UserRole::Admin], true);
    }

    public function update(User $user, TimetableEntry $model): bool
    {
        return $this->create($user);
    }

    public function delete(User $user, TimetableEntry $model): bool
    {
        return $this->create($user);
    }
}
