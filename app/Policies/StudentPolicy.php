<?php

namespace App\Policies;

use App\Enums\UserRole;
use App\Models\Student;
use App\Models\User;

class StudentPolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Student $model): bool
    {
        if (in_array($user->role, [UserRole::Founder, UserRole::Admin, UserRole::Scolarite], true)) {
            return true;
        }

        if ($user->role === UserRole::Parent) {
            return $model->parent_id === $user->id;
        }

        if ($user->role === UserRole::Teacher) {
            return $model->classroom && $user->teachesInClassroom($model->classroom);
        }

        return false;
    }

    public function create(User $user): bool
    {
        return in_array($user->role, [UserRole::Founder, UserRole::Admin], true);
    }

    public function update(User $user, Student $model): bool
    {
        return $this->create($user);
    }

    public function delete(User $user, Student $model): bool
    {
        return $this->create($user);
    }
}
