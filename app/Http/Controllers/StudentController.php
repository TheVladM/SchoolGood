<?php

namespace App\Http\Controllers;

use App\Enums\SchoolYearStatus;
use App\Enums\StudentSchoolYearStatus;
use App\Enums\UserRole;
use App\Models\Classroom;
use App\Models\SchoolYear;
use App\Models\Student;
use App\Models\StudentSchoolYearRecord;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class StudentController extends Controller
{
    public function index(Request $request): View
    {
        $statusScope = $request->input('status_scope', 'active');

        $students = $this->visibleStudentsQuery($request->user())
            ->with(['classroom', 'parent', 'schoolYearRecords.schoolYear'])
            ->when(
                $statusScope === 'active',
                fn ($query) => $query->where('is_active', true)
            )
            ->when(
                $statusScope === 'archives',
                fn ($query) => $query->where('is_active', false)
            )
            ->when(
                $request->filled('school_year_id'),
                fn ($query) => $query->whereHas('schoolYearRecords', fn ($records) => $records->where('school_year_id', $request->integer('school_year_id')))
            )
            ->latest()
            ->paginate(10);

        return view('students.index', [
            'students' => $students,
            'schoolYears' => SchoolYear::orderByDesc('starts_on')->get(),
            'statusScope' => $statusScope,
        ]);
    }

    public function create(Request $request): View
    {
        $this->authorize('create', Student::class);

        return view('students.create', [
            'classrooms' => Classroom::orderBy('name')->get(),
            'parents' => User::where('role', UserRole::Parent->value)->orderBy('name')->get(),
            'schoolYears' => SchoolYear::orderByDesc('starts_on')->get(),
            'selectedSchoolYearId' => $this->defaultSchoolYear()?->id,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $this->authorize('create', Student::class);

        [$studentData, $recordData] = $this->validatedData($request);
        $student = Student::create($studentData);
        $this->createSchoolYearRecord($student, $recordData);

        return redirect()
            ->route('students.index')
            ->with('success', 'Eleve cree avec succes.');
    }

    public function show(Request $request, Student $student): View
    {
        $this->authorize('view', $student);
        $student->load(['classroom', 'parent', 'payments', 'schoolYearRecords.schoolYear', 'schoolYearRecords.classroom', 'bookLoans.book']);

        return view('students.show', ['student' => $student]);
    }

    public function edit(Request $request, Student $student): View
    {
        $this->authorize('update', $student);

        return view('students.edit', [
            'student' => $student,
            'classrooms' => Classroom::orderBy('name')->get(),
            'parents' => User::where('role', UserRole::Parent->value)->orderBy('name')->get(),
            'schoolYears' => SchoolYear::orderByDesc('starts_on')->get(),
            'selectedSchoolYearId' => $student->schoolYearRecords()->first()?->school_year_id,
        ]);
    }

    public function update(Request $request, Student $student): RedirectResponse
    {
        $this->authorize('update', $student);

        [$studentData, $recordData] = $this->validatedData($request);
        $student->update($studentData);
        $this->updateCurrentSchoolYearRecord($student, $recordData);

        return redirect()
            ->route('students.index')
            ->with('success', 'Eleve mis a jour avec succes.');
    }

    public function destroy(Request $request, Student $student): RedirectResponse
    {
        $this->authorize('delete', $student);

        $currentRecord = $student->schoolYearRecords()->first();

        if ($currentRecord) {
            $currentRecord->update([
                'status' => StudentSchoolYearStatus::Withdrawn,
                'withdrawn_at' => now(),
            ]);
        }

        $previousRecord = $student->schoolYearRecords()
            ->where('status', '!=', StudentSchoolYearStatus::Withdrawn->value)
            ->whereKeyNot($currentRecord?->id)
            ->first();

        $student->update([
            'is_active' => false,
            'left_at' => now(),
            'classroom_id' => $previousRecord?->classroom_id ?? $student->classroom_id,
        ]);

        return redirect()
            ->route('students.index')
            ->with('success', 'Eleve archive avec succes. Son historique scolaire reste accessible.');
    }

    private function validatedData(Request $request): array
    {
        $data = $request->validate([
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'birth_date' => ['required', 'date', 'before:today'],
            'classroom_id' => ['required', 'exists:classrooms,id'],
            'parent_id' => [
                'required',
                Rule::exists('users', 'id')->where(fn ($query) => $query->where('role', UserRole::Parent->value)),
            ],
            'school_year_id' => ['required', 'exists:school_years,id'],
        ]);

        return [
            [
                'first_name' => $data['first_name'],
                'last_name' => $data['last_name'],
                'birth_date' => $data['birth_date'],
                'classroom_id' => $data['classroom_id'],
                'parent_id' => $data['parent_id'],
                'is_active' => true,
                'left_at' => null,
            ],
            [
                'school_year_id' => $data['school_year_id'],
                'classroom_id' => $data['classroom_id'],
            ],
        ];
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

    private function createSchoolYearRecord(Student $student, array $recordData): void
    {
        $schoolYear = SchoolYear::findOrFail($recordData['school_year_id']);
        $classroom = Classroom::findOrFail($recordData['classroom_id']);

        StudentSchoolYearRecord::create([
            'student_id' => $student->id,
            'school_year_id' => $schoolYear->id,
            'classroom_id' => $classroom->id,
            'classroom_name_snapshot' => $classroom->name,
            'level_snapshot' => $classroom->level,
            'section_snapshot' => $classroom->section?->value,
            'status' => $schoolYear->status === SchoolYearStatus::Planned
                ? StudentSchoolYearStatus::PreRegistered
                : StudentSchoolYearStatus::Active,
        ]);
    }

    private function updateCurrentSchoolYearRecord(Student $student, array $recordData): void
    {
        $schoolYear = SchoolYear::findOrFail($recordData['school_year_id']);
        $classroom = Classroom::findOrFail($recordData['classroom_id']);

        $record = $student->schoolYearRecords()->first();

        if (! $record) {
            $this->createSchoolYearRecord($student, $recordData);

            return;
        }

        $existingForYear = $student->schoolYearRecords()
            ->where('school_year_id', $schoolYear->id)
            ->whereKeyNot($record->id)
            ->exists();

        if ($existingForYear) {
            throw ValidationException::withMessages([
                'school_year_id' => 'Cet eleve possede deja un dossier pour cette annee scolaire.',
            ]);
        }

        $record->update([
            'school_year_id' => $schoolYear->id,
            'classroom_id' => $classroom->id,
            'classroom_name_snapshot' => $classroom->name,
            'level_snapshot' => $classroom->level,
            'section_snapshot' => $classroom->section?->value,
            'status' => $schoolYear->status === SchoolYearStatus::Planned
                ? StudentSchoolYearStatus::PreRegistered
                : StudentSchoolYearStatus::Active,
            'withdrawn_at' => null,
        ]);
    }

    private function defaultSchoolYear(): ?SchoolYear
    {
        return SchoolYear::query()
            ->whereIn('status', [SchoolYearStatus::Current->value, SchoolYearStatus::Planned->value])
            ->orderByRaw("
                case status
                    when 'current' then 1
                    when 'planned' then 2
                    else 3
                end
            ")
            ->orderByDesc('starts_on')
            ->first();
    }
}
