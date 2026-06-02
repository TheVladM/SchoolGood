<?php

namespace App\Http\Controllers;

use App\Enums\CourseDay;
use App\Enums\UserRole;
use App\Models\Classroom;
use App\Models\Course;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class CourseController extends Controller
{
    public function index(Request $request): View
    {
        $courses = $this->visibleCoursesQuery($request->user())
            ->with(['teacher', 'classroom'])
            ->latest()
            ->paginate(10);

        return view('courses.index', ['courses' => $courses]);
    }

    public function create(Request $request): View
    {
        $this->authorize('create', Course::class);

        return view('courses.create', [
            'classrooms' => $this->availableClassrooms($request->user()),
            'teachers' => $this->availableTeachers($request->user()),
            'days' => CourseDay::options(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $this->authorize('create', Course::class);

        $data = $this->validatedData($request);

        if ($request->user()->hasRole(UserRole::Teacher)) {
            $data['teacher_id'] = $request->user()->id;
        }

        try {
            Course::create($data);

            return redirect()
                ->route('courses.index')
                ->with('success', 'Cours cree avec succes.');
        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->with('error', 'Erreur lors de la création du cours. Veuillez réessayer.');
        }
    }

    public function show(Request $request, Course $course): View
    {
        $this->authorize('view', $course);
        $course->load(['teacher', 'classroom']);

        return view('courses.show', ['course' => $course]);
    }

    public function edit(Request $request, Course $course): View
    {
        $this->authorize('update', $course);

        return view('courses.edit', [
            'course' => $course,
            'classrooms' => $this->availableClassrooms($request->user()),
            'teachers' => $this->availableTeachers($request->user()),
            'days' => CourseDay::options(),
        ]);
    }

    public function update(Request $request, Course $course): RedirectResponse
    {
        $this->authorize('update', $course);

        $data = $this->validatedData($request);

        if ($request->user()->hasRole(UserRole::Teacher)) {
            $data['teacher_id'] = $request->user()->id;
        }

        try {
            $course->update($data);

            return redirect()
                ->route('courses.index')
                ->with('success', 'Cours mis a jour avec succes.');
        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->with('error', 'Erreur lors de la mise à jour du cours. Veuillez réessayer.');
        }
    }

    public function destroy(Request $request, Course $course): RedirectResponse
    {
        $this->authorize('delete', $course);

        $course->delete();

        return redirect()
            ->route('courses.index')
            ->with('success', 'Cours supprime avec succes.');
    }

    private function validatedData(Request $request): array
    {
        return $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'content' => ['required', 'string'],
            'teacher_id' => [
                'required',
                Rule::exists('users', 'id')->where(fn ($query) => $query->where('role', UserRole::Teacher->value)),
            ],
            'classroom_id' => ['required', 'exists:classrooms,id'],
            'day' => ['required', Rule::enum(CourseDay::class)],
        ]);
    }

    private function visibleCoursesQuery(User $user): Builder
    {
        if ($user->hasRole(UserRole::Parent)) {
            return Course::query()->whereHas('classroom.students', fn ($query) => $query->where('parent_id', $user->id));
        }

        if ($user->hasRole(UserRole::Teacher)) {
            return Course::query()->where('teacher_id', $user->id);
        }

        return Course::query();
    }

    private function ensureCourseVisible(User $user, Course $course): void
    {
        abort_unless(
            $this->visibleCoursesQuery($user)->whereKey($course->id)->exists(),
            403,
            'Vous ne pouvez pas consulter ce cours.'
        );
    }

    private function availableTeachers(User $user)
    {
        if ($user->hasRole(UserRole::Teacher)) {
            return User::whereKey($user->id)->get();
        }

        return User::where('role', UserRole::Teacher->value)->orderBy('name')->get();
    }

    private function availableClassrooms(User $user)
    {
        if ($user->hasRole(UserRole::Teacher)) {
            return Classroom::query()
                ->where(function ($query) use ($user): void {
                    $query
                        ->where('main_teacher_id', $user->id)
                        ->orWhere('language_teacher_id', $user->id);
                })
                ->orderBy('name')
                ->get();
        }

        return Classroom::orderBy('name')->get();
    }
}
