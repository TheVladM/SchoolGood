<?php

namespace App\Policies;

use App\Enums\UserRole;
use App\Models\BookLoan;
use App\Models\User;

class BookLoanPolicy
{
    public function view(User $user, BookLoan $model): bool
    {
        if (in_array($user->role, [UserRole::Founder, UserRole::Admin, UserRole::Scolarite], true)) {
            return true;
        }

        if ($user->is($model->user)) {
            return true;
        }

        if ($model->student && $model->student->parent_id === $user->id) {
            return true;
        }

        return false;
    }

    public function create(User $user): bool
    {
        return in_array($user->role, [UserRole::Founder, UserRole::Admin, UserRole::Scolarite], true);
    }

    public function update(User $user, BookLoan $model): bool
    {
        return $this->create($user);
    }

    public function delete(User $user, BookLoan $model): bool
    {
        return in_array($user->role, [UserRole::Founder, UserRole::Admin], true);
    }

    public function return(User $user, BookLoan $model): bool
    {
        return in_array($user->role, [UserRole::Founder, UserRole::Admin, UserRole::Scolarite], true);
    }

    public function chargePenalty(User $user, BookLoan $model): bool
    {
        return in_array($user->role, [UserRole::Founder, UserRole::Scolarite], true);
    }
}
