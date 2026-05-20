<?php

namespace App\Http\Controllers;

use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Routing\Controller as BaseController;

abstract class Controller extends BaseController
{
    use AuthorizesRequests;
    protected function authorizeRoles(User $user, array $roles): void
    {
        abort_unless(
            $user->hasAnyRole($roles),
            403,
            'Vous n\'avez pas l\'autorisation necessaire.'
        );
    }

    protected function isStaff(User $user): bool
    {
        return $user->hasAnyRole([
            UserRole::Founder,
            UserRole::Admin,
            UserRole::Scolarite,
            UserRole::Teacher,
        ]);
    }
}
