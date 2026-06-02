<?php

namespace App\Services;

use App\Enums\ClassroomSection;
use App\Models\Classroom;
use App\Models\Course;
use App\Models\TimetableEntry;
use Illuminate\Support\Collection;

class CourseTimetableSyncService
{
    public function syncEntry(TimetableEntry $entry): int
    {
        $classrooms = $this->classroomsForEntry($entry);
        $synced = 0;

        foreach ($classrooms as $classroom) {
            $this->upsertCourseFromEntry($entry, $classroom);
            $synced++;
        }

        return $synced;
    }

    public function syncEntryForClassroom(TimetableEntry $entry, Classroom $classroom): void
    {
        if ($classroom->level !== $entry->level || $classroom->section?->value !== $entry->section?->value) {
            return;
        }

        $this->upsertCourseFromEntry($entry, $classroom);
    }

    public function syncAllForClassroom(Classroom $classroom): int
    {
        $entries = TimetableEntry::query()
            ->where('level', $classroom->level)
            ->where('section', $classroom->section?->value)
            ->get();

        foreach ($entries as $entry) {
            $this->upsertCourseFromEntry($entry, $classroom);
        }

        return $entries->count();
    }

    public function removeCoursesForEntry(TimetableEntry $entry): void
    {
        Course::query()->where('timetable_entry_id', $entry->id)->delete();
    }

    private function classroomsForEntry(TimetableEntry $entry): Collection
    {
        return Classroom::query()
            ->where('level', $entry->level)
            ->where('section', $entry->section?->value)
            ->get();
    }

    private function upsertCourseFromEntry(TimetableEntry $entry, Classroom $classroom): void
    {
        $teacherId = $this->resolveTeacherId($classroom, $entry->subject);

        if (! $teacherId) {
            return;
        }

        $content = trim(($entry->notes ?? '')."\n\n".
            'Créneau : '.$entry->start_time.' — '.$entry->end_time);

        Course::updateOrCreate(
            [
                'timetable_entry_id' => $entry->id,
                'classroom_id' => $classroom->id,
            ],
            [
                'title' => $entry->subject,
                'content' => $content,
                'teacher_id' => $teacherId,
                'day' => $entry->day,
            ]
        );
    }

    private function resolveTeacherId(Classroom $classroom, string $subject): ?int
    {
        $normalized = strtolower($subject);

        if ($classroom->section === ClassroomSection::Francophone) {
            if ($this->isLanguageSubject($normalized, ['anglais', 'english'])) {
                return $classroom->language_teacher_id ?? $classroom->main_teacher_id;
            }
        }

        if ($classroom->section === ClassroomSection::Anglophone) {
            if ($this->isLanguageSubject($normalized, ['français', 'francais', 'french'])) {
                return $classroom->language_teacher_id ?? $classroom->main_teacher_id;
            }
        }

        return $classroom->main_teacher_id ?? $classroom->language_teacher_id;
    }

    /**
     * @param  array<int, string>  $keywords
     */
    private function isLanguageSubject(string $subject, array $keywords): bool
    {
        foreach ($keywords as $keyword) {
            if (str_contains($subject, $keyword)) {
                return true;
            }
        }

        return false;
    }
}
