<?php

namespace App\Http\Controllers;

use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function index(Request $request): View
    {
        $users = User::query()
            ->when(
                $request->filled('role'),
                fn ($query) => $query->where('role', $request->string('role'))
            )
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('users.index', [
            'users' => $users,
            'roles' => $this->availableRoles($request->user()),
        ]);
    }

    public function create(Request $request): View
    {
        return view('users.create', [
            'roles' => $this->availableRoles($request->user()),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validatedData($request);
        $this->guardFounderRole($request->user(), $data['role']);

        User::create($data);

        return redirect()
            ->route('users.index')
            ->with('success', 'Utilisateur cree avec succes.');
    }

    public function show(User $user): View
    {
        $user->loadCount(['children', 'courses', 'mainClassrooms']);

        return view('users.show', ['managedUser' => $user]);
    }

    public function edit(Request $request, User $user): View
    {
        $this->guardProtectedFounder($request->user(), $user);

        return view('users.edit', [
            'managedUser' => $user,
            'roles' => $this->availableRoles($request->user()),
        ]);
    }

    public function update(Request $request, User $user): RedirectResponse
    {
        $this->guardProtectedFounder($request->user(), $user);

        $data = $this->validatedData($request, $user);
        $this->guardFounderRole($request->user(), $data['role'], $user);

        if (blank($data['password'] ?? null)) {
            unset($data['password']);
        }

        $user->update($data);

        return redirect()
            ->route('users.index')
            ->with('success', 'Utilisateur mis a jour avec succes.');
    }

    public function destroy(Request $request, User $user): RedirectResponse
    {
        abort_if(
            $request->user()->is($user),
            422,
            'Vous ne pouvez pas supprimer votre propre compte.'
        );

        $this->guardProtectedFounder($request->user(), $user);

        $user->delete();

        return redirect()
            ->route('users.index')
            ->with('success', 'Utilisateur supprime avec succes.');
    }

    private function validatedData(Request $request, ?User $user = null): array
    {
        $passwordRules = $user
            ? ['nullable', 'confirmed', 'min:8']
            : ['required', 'confirmed', 'min:8'];

        return $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user?->id)],
            'phone' => ['nullable', 'string', 'max:255'],
            'role' => ['required', Rule::enum(UserRole::class)],
            'password' => $passwordRules,
        ]);
    }

    private function availableRoles(User $actor): array
    {
        $roles = UserRole::options();

        if ($actor->hasRole(UserRole::Founder)) {
            return $roles;
        }

        unset($roles[UserRole::Founder->value]);

        return $roles;
    }

    private function guardFounderRole(User $actor, string $role, ?User $managedUser = null): void
    {
        if ($role === UserRole::Founder->value) {
            abort_unless(
                $actor->hasRole(UserRole::Founder),
                403,
                'Seul le fondateur peut attribuer ce role.'
            );
        }

        if ($managedUser && $managedUser->hasRole(UserRole::Founder) && ! $actor->hasRole(UserRole::Founder)) {
            abort(403, 'Seul le fondateur peut modifier ce compte.');
        }
    }

    private function guardProtectedFounder(User $actor, User $managedUser): void
    {
        if ($managedUser->hasRole(UserRole::Founder) && ! $actor->hasRole(UserRole::Founder)) {
            abort(403, 'Seul le fondateur peut modifier ce compte.');
        }
    }
}
