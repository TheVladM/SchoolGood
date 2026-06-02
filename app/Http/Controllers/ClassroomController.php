<?php

namespace App\Http\Controllers;

use App\Enums\ClassroomCycleType;
use App\Enums\ClassroomSection;
use App\Enums\SchoolLevel;
use App\Enums\UserRole;
use App\Models\Classroom;
use App\Models\Room;
use App\Models\User;
use App\Services\ClassroomAssignmentValidator;
use App\Services\ClassroomTitularCourseService;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class ClassroomController extends Controller
{
    public function __construct(
        private ClassroomAssignmentValidator $assignmentValidator,
        private ClassroomTitularCourseService $titularCourseService,
    ) {}

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
        $this->authorize('create', Classroom::class);

        return view('classrooms.create', [
            'teachers' => User::where('role', UserRole::Teacher->value)->orderBy('name')->get(),
            'sections' => ClassroomSection::options(),
            'cycleTypes' => ClassroomCycleType::options(),
            'rooms' => Room::orderBy('name')->get(),
            'levelsBySection' => [
                'francophone' => SchoolLevel::optionsForSection(ClassroomSection::Francophone),
                'anglophone' => SchoolLevel::optionsForSection(ClassroomSection::Anglophone),
                'bilingue' => SchoolLevel::optionsForSection(ClassroomSection::Bilingue),
            ],
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $this->authorize('create', Classroom::class);

        Classroom::create($this->validatedData($request));

        return redirect()
            ->route('classrooms.index')
            ->with('success', 'Classe creee avec succes.');
    }

    public function show(Request $request, Classroom $classroom): View
    {
        $this->ensureClassroomVisible($request->user(), $classroom);
        $classroom->load(['mainTeacher', 'languageTeacher', 'students.parent', 'courses.teacher', 'timetableEntries']);

        return view('classrooms.show', ['classroom' => $classroom]);
    }

    public function edit(Request $request, Classroom $classroom): View
    {
        $this->authorize('update', $classroom);

        return view('classrooms.edit', [
            'classroom' => $classroom,
            'teachers' => User::where('role', UserRole::Teacher->value)->orderBy('name')->get(),
            'sections' => ClassroomSection::options(),
            'cycleTypes' => ClassroomCycleType::options(),
            'rooms' => Room::orderBy('name')->get(),
            'levelsBySection' => [
                'francophone' => SchoolLevel::optionsForSection(ClassroomSection::Francophone),
                'anglophone' => SchoolLevel::optionsForSection(ClassroomSection::Anglophone),
                'bilingue' => SchoolLevel::optionsForSection(ClassroomSection::Bilingue),
            ],
        ]);
    }

    public function update(Request $request, Classroom $classroom): RedirectResponse
    {
        $this->authorize('update', $classroom);

        $classroom->update($this->validatedData($request, $classroom));

        return redirect()
            ->route('classrooms.index')
            ->with('success', 'Classe mise a jour avec succes.');
    }

    public function setupTitularCourses(Request $request, Classroom $classroom): RedirectResponse
    {
        $this->authorize('update', $classroom);

        $result = $this->titularCourseService->setup($classroom);

        return back()->with(
            'success',
            "Programme titulaire : {$result['timetable']} créneau(x) d'emploi du temps et {$result['subjects']} matière(s) ajoutée(s)."
        );
    }

    public function destroy(Request $request, Classroom $classroom): RedirectResponse
    {
        $this->authorize('delete', $classroom);

        $classroom->delete();

        return redirect()
            ->route('classrooms.index')
            ->with('success', 'Classe supprimee avec succes.');
    }

    private function validatedData(Request $request, ?Classroom $classroom = null): array
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'level' => ['required', 'string', 'max:255'],
            'section' => ['required', Rule::enum(ClassroomSection::class)],
            'cycle_type' => ['required', Rule::enum(ClassroomCycleType::class)],
            'room_id' => ['nullable', 'exists:rooms,id'],
            'room' => ['nullable', 'string', 'max:255'],
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

        if (
            filled($data['main_teacher_id'] ?? null)
            && ($data['main_teacher_id'] === ($data['language_teacher_id'] ?? null))
        ) {
            throw ValidationException::withMessages([
                'language_teacher_id' => 'Le titulaire et l enseignant de langue doivent etre differents.',
            ]);
        }

        if ($this->requiresTwoTeachers($data['level'], $data['section'])) {
            if (blank($data['main_teacher_id'] ?? null)) {
                throw ValidationException::withMessages([
                    'main_teacher_id' => 'Cette classe doit avoir un enseignant titulaire.',
                ]);
            }

            if (blank($data['language_teacher_id'] ?? null)) {
                throw ValidationException::withMessages([
                    'language_teacher_id' => 'Cette classe doit avoir un enseignant de langue.',
                ]);
            }
        }

        if (filled($data['main_teacher_id'] ?? null)) {
            $mainTeacher = User::find($data['main_teacher_id']);
            if ($mainTeacher && ! $this->isTeacherLanguageCompatible($mainTeacher, $data['section'])) {
                throw ValidationException::withMessages([
                    'main_teacher_id' => 'La langue d\'enseignement du titulaire ne correspond pas à la section de la classe.',
                ]);
            }
        }

        $this->assignmentValidator->validate($data, $classroom);

        if (filled($data['room_id'] ?? null)) {
            $room = Room::find($data['room_id']);
            $data['room'] = $room?->name ?? $data['room'];
            $data['location'] = trim(($room?->building ?? '').' '.($room?->floor ?? '')) ?: $data['location'];
        }

        abort_if(blank($data['room'] ?? null) && blank($data['room_id'] ?? null), 422, 'Veuillez sélectionner ou saisir une salle.');

        return $data;
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

    private function requiresTwoTeachers(string $level, string $section): bool
    {
        $normalizedLevel = strtolower(trim($level));

        return in_array($section, [
            ClassroomSection::Francophone->value,
            ClassroomSection::Anglophone->value,
        ], true) && in_array($normalizedLevel, [
            'sil',
            'cp',
            'ce1',
            'ce2',
            'cm1',
            'cm2',
            'class 1',
            'class 2',
            'class 3',
            'class 4',
            'class 5',
            'class 6',
        ], true);
    }

    private function isTeacherLanguageCompatible(User $teacher, string $classroomSection): bool
    {
        $teacherLanguage = $teacher->teaches_language?->value;
        
        // If teacher is bilingual, compatible with all sections
        if ($teacherLanguage === 'bilingual') {
            return true;
        }

        // Check language compatibility
        if ($classroomSection === ClassroomSection::Francophone->value) {
            return in_array($teacherLanguage, ['french', 'bilingual']);
        }

        if ($classroomSection === ClassroomSection::Anglophone->value) {
            return in_array($teacherLanguage, ['english', 'bilingual']);
        }

        // Bilingue classrooms accept all teacher languages
        return true;
    }

    protected function authorizeRoles(User $user, array $allowedRoles): void
    {
        abort_unless(
            in_array($user->role, $allowedRoles, true),
            403,
            'Vous n\'avez pas la permission d\'acceder a cette ressource.'
        );
    }
}
