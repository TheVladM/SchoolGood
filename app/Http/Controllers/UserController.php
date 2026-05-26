<?php

namespace App\Http\Controllers;

use App\Enums\UserDepartment;
use App\Enums\UserRole;
use App\Enums\TeacherLanguage;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

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
        $this->authorize('create', User::class);

        return view('users.create', [
            'roles' => $this->availableRoles($request->user()),
            'departments' => UserDepartment::options(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $this->authorize('create', User::class);

        $data = $this->normalizedUserData($this->validatedData($request));
        $this->guardFounderRole($request->user(), $data['role']);

        User::create($data);

        return redirect()
            ->route('users.index')
            ->with('success', 'Utilisateur cree avec succes.');
    }

    public function show(User $user): View
    {
        $this->authorize('view', $user);

        $user->loadCount(['children', 'courses', 'mainClassrooms']);

        return view('users.show', ['managedUser' => $user]);
    }

    public function edit(Request $request, User $user): View
    {
        $this->authorize('update', $user);

        return view('users.edit', [
            'managedUser' => $user,
            'roles' => $this->availableRoles($request->user()),
            'departments' => UserDepartment::options(),
        ]);
    }

    public function update(Request $request, User $user): RedirectResponse
    {
        $this->authorize('update', $user);

        abort_if(
            $user->hasRole(UserRole::Founder) && ! $request->user()->hasRole(UserRole::Founder),
            403,
            'Seul le fondateur peut modifier le compte du fondateur.'
        );

        $data = $this->normalizedUserData($this->validatedData($request, $user));
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

        abort_if(
            $user->hasRole(UserRole::Founder),
            403,
            'Le compte du fondateur ne peut pas etre supprime.'
        );

        $this->authorize('delete', $user);

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
            'department' => ['nullable', Rule::enum(UserDepartment::class)],
            'job_title' => ['nullable', 'string', 'max:255'],
            'teaches_language' => ['nullable', Rule::enum(TeacherLanguage::class)],
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

    private function normalizedUserData(array $data): array
    {
        if ($data['role'] === UserRole::Teacher->value) {
            $data['department'] = UserDepartment::Teaching->value;
            
            // Ensure teacher has a language set
            if (blank($data['teaches_language'] ?? null)) {
                throw ValidationException::withMessages([
                    'teaches_language' => 'Veuillez renseigner la langue d\'enseignement du professeur.',
                ]);
            }
        } else {
            // Non-teachers should have null teaches_language
            $data['teaches_language'] = null;
        }

        if ($data['role'] === UserRole::Scolarite->value && blank($data['department'] ?? null)) {
            $data['department'] = UserDepartment::Scolarite->value;
        }

        if ($data['role'] === UserRole::Parent->value) {
            $data['department'] = null;
            $data['job_title'] = null;

            return $data;
        }

        if (blank($data['department'] ?? null)) {
            throw ValidationException::withMessages([
                'department' => 'Veuillez renseigner le service ou le departement de cette personne.',
            ]);
        }

        return $data;
    }
}
