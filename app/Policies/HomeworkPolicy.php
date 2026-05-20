<?php

namespace App\Policies;

use App\Models\Homework;
use App\Models\User;
use App\Enums\UserRole;

class HomeworkPolicy
{
    public function viewAny(User $user): bool
    {
        // Founder, Admin, Scolarite, Teachers can view all homeworks
        // Parents can only view their children's homeworks
        return in_array($user->role, [UserRole::Founder, UserRole::Admin, UserRole::Scolarite, UserRole::Teacher]);
    }

    public function view(User $user, Homework $homework): bool
    {
        // Teachers can view their own homeworks
        if ($user->role === UserRole::Teacher && $user->id === $homework->teacher_id) {
            return true;
        }

        // Founder and Admin can view all homeworks
        if (in_array($user->role, [UserRole::Founder, UserRole::Admin])) {
            return true;
        }

        // Scolarite can view all homeworks
        if ($user->role === UserRole::Scolarite) {
            return true;
        }

        // Parents can view homeworks of their children's classroom
        if ($user->role === UserRole::Parent) {
            return $user->children()->whereHas('classroom', function ($query) use ($homework) {
                $query->where('id', $homework->classroom_id);
            })->exists();
        }

        return false;
    }

    public function create(User $user): bool
    {
        // Only Teachers, Admin, and Founder can create homeworks
        return in_array($user->role, [UserRole::Founder, UserRole::Admin, UserRole::Teacher]);
    }

    public function update(User $user, Homework $homework): bool
    {
        // Only the teacher who created it can update, or Founder/Admin
        if (in_array($user->role, [UserRole::Founder, UserRole::Admin])) {
            return true;
        }

        if ($user->role === UserRole::Teacher && $user->id === $homework->teacher_id) {
            return true;
        }

        return false;
    }

    public function delete(User $user, Homework $homework): bool
    {
        // Only the teacher who created it can delete, or Founder/Admin
        if (in_array($user->role, [UserRole::Founder, UserRole::Admin])) {
            return true;
        }

        if ($user->role === UserRole::Teacher && $user->id === $homework->teacher_id) {
            return true;
        }

        return false;
    }

    public function restore(User $user, Homework $homework): bool
    {
        return $this->delete($user, $homework);
    }

    public function forceDelete(User $user, Homework $homework): bool
    {
        return $this->delete($user, $homework);
    }
}
