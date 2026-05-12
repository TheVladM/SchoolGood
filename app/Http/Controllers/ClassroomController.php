<?php

namespace App\Http\Controllers;

use App\Enums\ClassroomSection;
use App\Enums\UserRole;
use App\Models\Classroom;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ClassroomController extends Controller
{
    public function index(Request $request): View
    {
        $classrooms = $this->visibleClassroomsQuery($request->user())
            ->with(['mainTeacher', 'languageTeacher'])
            ->withCount('students')
            ->latest()
            ->paginate(10);

        return view('classrooms.index', ['classrooms' => $classrooms]);
    }

    public function create(Request $request): View
    {
        $this->authorizeRoles($request->user(), [
            UserRole::Founder,
            UserRole::Admin,
            UserRole::Scolarite,
        ]);

        return view('classrooms.create', [
            'teachers' => User::where('role', UserRole::Teacher->value)->orderBy('name')->get(),
            'sections' => ClassroomSection::options(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $this->authorizeRoles($request->user(), [
            UserRole::Founder,
            UserRole::Admin,
            UserRole::Scolarite,
        ]);

        Classroom::create($this->validatedData($request));

        return redirect()
            ->route('classrooms.index')
            ->with('success', 'Classe creee avec succes.');
    }

    public function show(Request $request, Classroom $classroom): View
    {
        $this->ensureClassroomVisible($request->user(), $classroom);
        $classroom->load(['mainTeacher', 'languageTeacher', 'students.parent', 'courses.teacher']);

        return view('classrooms.show', ['classroom' => $classroom]);
    }

    public function edit(Request $request, Classroom $classroom): View
    {
        $this->authorizeRoles($request->user(), [
            UserRole::Founder,
            UserRole::Admin,
            UserRole::Scolarite,
        ]);

        return view('classrooms.edit', [
            'classroom' => $classroom,
            'teachers' => User::where('role', UserRole::Teacher->value)->orderBy('name')->get(),
            'sections' => ClassroomSection::options(),
        ]);
    }

    public function update(Request $request, Classroom $classroom): RedirectResponse
    {
        $this->authorizeRoles($request->user(), [
            UserRole::Founder,
            UserRole::Admin,
            UserRole::Scolarite,
        ]);

        $classroom->update($this->validatedData($request));

        return redirect()
            ->route('classrooms.index')
            ->with('success', 'Classe mise a jour avec succes.');
    }

    public function destroy(Request $request, Classroom $classroom): RedirectResponse
    {
        $this->authorizeRoles($request->user(), [
            UserRole::Founder,
            UserRole::Admin,
            UserRole::Scolarite,
        ]);

        $classroom->delete();

        return redirect()
            ->route('classrooms.index')
            ->with('success', 'Classe supprimee avec succes.');
    }

    private function validatedData(Request $request): array
    {
        return $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'level' => ['required', 'string', 'max:255'],
            'section' => ['required', Rule::enum(ClassroomSection::class)],
            'room' => ['required', 'string', 'max:255'],
            'location' => ['nullable', 'string', 'max:255'],
            'main_teacher_id' => [
                'nullable',
                Rule::exists('users', 'id')->where(fn ($query) => $query->where('role', UserRole::Teacher->value)),
            ],
            'language_teacher_id' => [
                'nullable',
                Rule::exists('users', 'id')->where(fn ($query) => $query->where('role', UserRole::Teacher->value)),
            ],
        ]);
    }

    private function visibleClassroomsQuery(User $user): Builder
    {
        if ($user->hasRole(UserRole::Parent)) {
            return Classroom::query()->whereHas('students', fn ($query) => $query->where('parent_id', $user->id));
        }

        if ($user->hasRole(UserRole::Teacher)) {
            return Classroom::query()->where(function ($query) use ($user): void {
                $query
                    ->where('main_teacher_id', $user->id)
                    ->orWhere('language_teacher_id', $user->id);
            });
        }

        return Classroom::query();
    }

    private function ensureClassroomVisible(User $user, Classroom $classroom): void
    {
        abort_unless(
            $this->visibleClassroomsQuery($user)->whereKey($classroom->id)->exists(),
            403,
            'Vous ne pouvez pas consulter cette classe.'
        );
    }
}
