<?php

namespace App\Http\Controllers;

use App\Enums\HomeworkStatus;
use App\Enums\HomeworkSubmissionStatus;
use App\Enums\UserRole;
use App\Models\Classroom;
use App\Models\Homework;
use App\Models\HomeworkSubmission;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class HomeworkController extends Controller
{
    public function index()
    {
        $this->authorize('viewAny', Homework::class);

        $user = auth()->user();
        $query = Homework::with(['teacher', 'classroom']);

        if ($user->role === UserRole::Parent) {
            $childrenClassroomIds = $user->children()
                ->pluck('classroom_id')
                ->toArray();

            $query->whereIn('classroom_id', $childrenClassroomIds);
        }

        if ($user->role === UserRole::Teacher) {
            $query->where('teacher_id', $user->id);
        }

        $homeworks = $query->orderBy('due_date', 'desc')
            ->paginate(15);

        return view('homeworks.index', compact('homeworks'));
    }

    public function create()
    {
        $this->authorize('create', Homework::class);

        return view('homeworks.create', [
            'classrooms' => $this->availableClassrooms(),
            'teachers' => $this->availableTeachers(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $this->authorize('create', Homework::class);

        $data = $this->validatedData($request);
        $this->guardTeacherClassroom($request, $data);

        $data['status'] = $data['status'] ?? HomeworkStatus::Assigned->value;

        $homework = Homework::create($data);
        $this->ensureSubmissions($homework);

        return redirect()->route('homeworks.index')->with('success', 'Devoir créé avec succès');
    }

    public function show(Homework $homework)
    {
        $this->authorize('view', $homework);

        $this->ensureSubmissions($homework);

        $homework->load([
            'teacher',
            'classroom.students.parent',
            'submissions.student',
        ]);

        $parentChildren = auth()->user()->hasRole(UserRole::Parent)
            ? auth()->user()->children()->pluck('id')
            : collect();

        return view('homeworks.show', [
            'homework' => $homework,
            'parentChildren' => $parentChildren,
        ]);
    }

    public function edit(Homework $homework)
    {
        $this->authorize('update', $homework);

        return view('homeworks.edit', [
            'homework' => $homework,
            'classrooms' => $this->availableClassrooms(),
            'teachers' => $this->availableTeachers(),
        ]);
    }

    public function update(Request $request, Homework $homework): RedirectResponse
    {
        $this->authorize('update', $homework);

        $data = $this->validatedData($request);
        $this->guardTeacherClassroom($request, $data);

        $homework->update($data);

        return redirect()->route('homeworks.show', $homework)->with('success', 'Devoir mis à jour avec succès');
    }

    public function destroy(Homework $homework): RedirectResponse
    {
        $this->authorize('delete', $homework);

        $homework->delete();

        return redirect()->route('homeworks.index')->with('success', 'Devoir supprimé avec succès');
    }

    private function validatedData(Request $request): array
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'subject' => 'nullable|string|max:100',
            'teacher_id' => 'required|exists:users,id',
            'classroom_id' => 'required|exists:classrooms,id',
            'due_date' => 'required|date_format:Y-m-d\TH:i|after:now',
            'attachments' => 'nullable|array',
        ], [
            'due_date.date_format' => 'La date limite doit être au format YYYY-MM-DD HH:MM (ex: 2026-05-28 15:30)',
            'due_date.after' => 'La date limite doit être dans le futur',
        ]);

        if ($request->user()->hasRole(UserRole::Teacher)) {
            $data['teacher_id'] = $request->user()->id;
        }

        return $data;
    }

    private function guardTeacherClassroom(Request $request, array $data): void
    {
        if (! $request->user()->hasRole(UserRole::Teacher)) {
            return;
        }

        if (! $request->user()->teachesInClassroom((int) $data['classroom_id'])) {
            throw ValidationException::withMessages([
                'classroom_id' => 'Vous ne pouvez creer un devoir que pour une classe ou vous enseignez.',
            ]);
        }
    }

    private function availableClassrooms()
    {
        $user = auth()->user();

        if ($user->hasRole(UserRole::Teacher)) {
            return $user->assignedClassroomsQuery()->get();
        }

        return Classroom::orderBy('name')->get();
    }

    private function availableTeachers()
    {
        $user = auth()->user();

        if ($user->hasRole(UserRole::Teacher)) {
            return User::whereKey($user->id)->get();
        }

        return User::where('role', UserRole::Teacher)->orderBy('name')->get();
    }

    private function ensureSubmissions(Homework $homework): void
    {
        $homework->loadMissing('classroom.students');

        foreach ($homework->classroom?->students ?? [] as $student) {
            HomeworkSubmission::firstOrCreate(
                [
                    'homework_id' => $homework->id,
                    'student_id' => $student->id,
                ],
                [
                    'status' => HomeworkSubmissionStatus::Pending,
                ]
            );
        }
    }
}
