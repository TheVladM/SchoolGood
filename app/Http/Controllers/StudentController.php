<?php

namespace App\Http\Controllers;

use App\Enums\UserRole;
use App\Models\Classroom;
use App\Models\Student;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class StudentController extends Controller
{
    public function index(Request $request): View
    {
        $students = $this->visibleStudentsQuery($request->user())
            ->with(['classroom', 'parent'])
            ->latest()
            ->paginate(10);

        return view('students.index', ['students' => $students]);
    }

    public function create(Request $request): View
    {
        $this->authorizeRoles($request->user(), [
            UserRole::Founder,
            UserRole::Admin,
            UserRole::Scolarite,
        ]);

        return view('students.create', [
            'classrooms' => Classroom::orderBy('name')->get(),
            'parents' => User::where('role', UserRole::Parent->value)->orderBy('name')->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $this->authorizeRoles($request->user(), [
            UserRole::Founder,
            UserRole::Admin,
            UserRole::Scolarite,
        ]);

        Student::create($this->validatedData($request));

        return redirect()
            ->route('students.index')
            ->with('success', 'Eleve cree avec succes.');
    }

    public function show(Request $request, Student $student): View
    {
        $this->ensureStudentVisible($request->user(), $student);
        $student->load(['classroom', 'parent', 'payments']);

        return view('students.show', ['student' => $student]);
    }

    public function edit(Request $request, Student $student): View
    {
        $this->authorizeRoles($request->user(), [
            UserRole::Founder,
            UserRole::Admin,
            UserRole::Scolarite,
        ]);

        return view('students.edit', [
            'student' => $student,
            'classrooms' => Classroom::orderBy('name')->get(),
            'parents' => User::where('role', UserRole::Parent->value)->orderBy('name')->get(),
        ]);
    }

    public function update(Request $request, Student $student): RedirectResponse
    {
        $this->authorizeRoles($request->user(), [
            UserRole::Founder,
            UserRole::Admin,
            UserRole::Scolarite,
        ]);

        $student->update($this->validatedData($request));

        return redirect()
            ->route('students.index')
            ->with('success', 'Eleve mis a jour avec succes.');
    }

    public function destroy(Request $request, Student $student): RedirectResponse
    {
        $this->authorizeRoles($request->user(), [
            UserRole::Founder,
            UserRole::Admin,
            UserRole::Scolarite,
        ]);

        $student->delete();

        return redirect()
            ->route('students.index')
            ->with('success', 'Eleve supprime avec succes.');
    }

    private function validatedData(Request $request): array
    {
        return $request->validate([
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'birth_date' => ['required', 'date', 'before:today'],
            'classroom_id' => ['required', 'exists:classrooms,id'],
            'parent_id' => [
                'required',
                Rule::exists('users', 'id')->where(fn ($query) => $query->where('role', UserRole::Parent->value)),
            ],
        ]);
    }

    private function visibleStudentsQuery(User $user): Builder
    {
        if ($user->hasRole(UserRole::Parent)) {
            return Student::query()->where('parent_id', $user->id);
        }

        if ($user->hasRole(UserRole::Teacher)) {
            return Student::query()->whereHas('classroom', function ($query) use ($user): void {
                $query
                    ->where('main_teacher_id', $user->id)
                    ->orWhere('language_teacher_id', $user->id);
            });
        }

        return Student::query();
    }

    private function ensureStudentVisible(User $user, Student $student): void
    {
        abort_unless(
            $this->visibleStudentsQuery($user)->whereKey($student->id)->exists(),
            403,
            'Vous ne pouvez pas consulter cet eleve.'
        );
    }
}
