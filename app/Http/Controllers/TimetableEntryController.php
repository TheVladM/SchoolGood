<?php

namespace App\Http\Controllers;

use App\Enums\ClassroomSection;
use App\Enums\CourseDay;
use App\Enums\UserRole;
use App\Models\Classroom;
use App\Models\Student;
use App\Models\TimetableEntry;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class TimetableEntryController extends Controller
{
    public function index(Request $request): View
    {
        $entries = $this->visibleTimetableEntriesQuery($request->user())
            ->paginate(15);

        return view('timetable_entries.index', [
            'entries' => $entries,
        ]);
    }

    public function create(Request $request): View
    {
        $this->authorizeScheduleManagement($request->user());

        return view('timetable_entries.create', [
            'sections' => ClassroomSection::options(),
            'days' => CourseDay::options(),
            'levels' => Classroom::query()->select('level')->distinct()->orderBy('level')->pluck('level'),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $this->authorizeScheduleManagement($request->user());

        TimetableEntry::create($this->validatedData($request));

        return redirect()
            ->route('timetable-entries.index')
            ->with('success', 'Emploi du temps enregistre avec succes.');
    }

    public function show(Request $request, TimetableEntry $timetableEntry): View
    {
        $this->ensureTimetableEntryVisible($request->user(), $timetableEntry);

        return view('timetable_entries.show', ['entry' => $timetableEntry]);
    }

    public function edit(Request $request, TimetableEntry $timetableEntry): View
    {
        $this->authorizeScheduleManagement($request->user());

        return view('timetable_entries.edit', [
            'entry' => $timetableEntry,
            'sections' => ClassroomSection::options(),
            'days' => CourseDay::options(),
            'levels' => Classroom::query()->select('level')->distinct()->orderBy('level')->pluck('level'),
        ]);
    }

    public function update(Request $request, TimetableEntry $timetableEntry): RedirectResponse
    {
        $this->authorizeScheduleManagement($request->user());

        $timetableEntry->update($this->validatedData($request));

        return redirect()
            ->route('timetable-entries.index')
            ->with('success', 'Emploi du temps mis a jour avec succes.');
    }

    public function destroy(Request $request, TimetableEntry $timetableEntry): RedirectResponse
    {
        $this->authorizeScheduleManagement($request->user());

        $timetableEntry->delete();

        return redirect()
            ->route('timetable-entries.index')
            ->with('success', 'Emploi du temps supprime avec succes.');
    }

    private function validatedData(Request $request): array
    {
        return $request->validate([
            'level' => ['required', 'string', 'max:255'],
            'section' => ['required', Rule::enum(ClassroomSection::class)],
            'subject' => ['required', 'string', 'max:255'],
            'day' => ['required', Rule::enum(CourseDay::class)],
            'start_time' => ['required', 'date_format:H:i'],
            'end_time' => ['required', 'date_format:H:i', 'after:start_time'],
            'notes' => ['nullable', 'string'],
        ]);
    }

    private function visibleTimetableEntriesQuery(User $user): Builder
    {
        if ($user->hasRole(UserRole::Parent)) {
            $pairs = Student::query()
                ->with('classroom')
                ->where('parent_id', $user->id)
                ->get()
                ->map(fn (Student $student) => [
                    'level' => $student->classroom?->level,
                    'section' => $student->classroom?->section?->value,
                ])
                ->filter(fn (array $pair) => filled($pair['level']) && filled($pair['section']))
                ->unique(fn (array $pair) => $pair['level'].'|'.$pair['section'])
                ->values();

            return $this->queryForPairs($pairs);
        }

        if ($user->hasRole(UserRole::Teacher)) {
            $pairs = Classroom::query()
                ->where(function ($query) use ($user): void {
                    $query
                        ->where('main_teacher_id', $user->id)
                        ->orWhere('language_teacher_id', $user->id);
                })
                ->get()
                ->map(fn (Classroom $classroom) => [
                    'level' => $classroom->level,
                    'section' => $classroom->section?->value,
                ])
                ->unique(fn (array $pair) => $pair['level'].'|'.$pair['section'])
                ->values();

            return $this->queryForPairs($pairs);
        }

        return TimetableEntry::query()
            ->orderByRaw("
                case day
                    when 'Lundi' then 1
                    when 'Mardi' then 2
                    when 'Mercredi' then 3
                    when 'Jeudi' then 4
                    when 'Vendredi' then 5
                    when 'Samedi' then 6
                    else 7
                end
            ")
            ->orderBy('start_time');
    }

    private function queryForPairs($pairs): Builder
    {
        if ($pairs->isEmpty()) {
            return TimetableEntry::query()->whereRaw('1 = 0');
        }

        return TimetableEntry::query()
            ->where(function ($query) use ($pairs): void {
                foreach ($pairs as $pair) {
                    $query->orWhere(function ($subQuery) use ($pair): void {
                        $subQuery
                            ->where('level', $pair['level'])
                            ->where('section', $pair['section']);
                    });
                }
            })
            ->orderByRaw("
                case day
                    when 'Lundi' then 1
                    when 'Mardi' then 2
                    when 'Mercredi' then 3
                    when 'Jeudi' then 4
                    when 'Vendredi' then 5
                    when 'Samedi' then 6
                    else 7
                end
            ")
            ->orderBy('start_time');
    }

    private function ensureTimetableEntryVisible(User $user, TimetableEntry $entry): void
    {
        abort_unless(
            $this->visibleTimetableEntriesQuery($user)->whereKey($entry->id)->exists(),
            403,
            'Vous ne pouvez pas consulter cet emploi du temps.'
        );
    }

    private function authorizeScheduleManagement(User $user): void
    {
        $this->authorizeRoles($user, [
            UserRole::Founder,
            UserRole::Admin,
            UserRole::Scolarite,
        ]);
    }
}
