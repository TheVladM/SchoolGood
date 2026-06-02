<?php

namespace App\Policies;

use App\Enums\UserRole;
use App\Models\Course;
use App\Models\User;

class CoursePolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Course $model): bool
    {
        if (in_array($user->role, [UserRole::Founder, UserRole::Admin, UserRole::Scolarite], true)) {
            return true;
        }

        if ($user->role === UserRole::Teacher) {
            return $model->teacher_id === $user->id;
        }

        if ($user->role === UserRole::Parent) {
            return $model->classroom?->students()->where('parent_id', $user->id)->exists() ?? false;
        }

        return false;
    }

    public function create(User $user): bool
    {
        return in_array($user->role, [UserRole::Founder, UserRole::Admin, UserRole::Teacher], true);
    }

    public function update(User $user, Course $model): bool
    {
        if (in_array($user->role, [UserRole::Founder, UserRole::Admin], true)) {
            return true;
        }

        return $user->role === UserRole::Teacher && $model->teacher_id === $user->id;
    }

    public function delete(User $user, Course $model): bool
    {
        return $this->update($user, $model);
    }
}
