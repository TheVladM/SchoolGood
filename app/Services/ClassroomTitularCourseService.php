<?php

namespace App\Services;

use App\Enums\ClassroomSection;
use App\Enums\CourseDay;
use App\Models\Classroom;
use App\Models\Course;
use App\Models\TimetableEntry;

class ClassroomTitularCourseService
{
    public function __construct(private CourseTimetableSyncService $timetableSync) {}

    /**
     * @return array{timetable: int, subjects: int}
     */
    public function setup(Classroom $classroom): array
    {
        $classroom->loadMissing(['mainTeacher', 'languageTeacher']);

        $fromTimetable = $this->timetableSync->syncAllForClassroom($classroom);
        $fromSubjects = $this->createStandardSubjectCourses($classroom);

        return [
            'timetable' => $fromTimetable,
            'subjects' => $fromSubjects,
        ];
    }

    private function createStandardSubjectCourses(Classroom $classroom): int
    {
        $created = 0;
        $subjects = $this->standardSubjects($classroom);

        foreach ($subjects as $config) {
            $teacherId = $config['language']
                ? ($classroom->language_teacher_id ?? $classroom->main_teacher_id)
                : ($classroom->main_teacher_id ?? $classroom->language_teacher_id);

            if (! $teacherId) {
                continue;
            }

            $day = $this->dayForSubject($classroom, $config['title']) ?? CourseDay::Monday;

            $exists = Course::query()
                ->where('classroom_id', $classroom->id)
                ->where('title', $config['title'])
                ->whereNull('timetable_entry_id')
                ->exists();

            if ($exists) {
                continue;
            }

            Course::create([
                'title' => $config['title'],
                'content' => 'Programme titulaire — '.$classroom->name,
                'teacher_id' => $teacherId,
                'classroom_id' => $classroom->id,
                'day' => $day,
                'timetable_entry_id' => null,
            ]);

            $created++;
        }

        return $created;
    }

    /**
     * @return array<int, array{title: string, language: bool}>
     */
    private function standardSubjects(Classroom $classroom): array
    {
        $main = [
            ['title' => 'Français', 'language' => false],
            ['title' => 'Mathématiques', 'language' => false],
            ['title' => 'Sciences', 'language' => false],
            ['title' => 'Histoire-Géographie', 'language' => false],
            ['title' => 'Informatique', 'language' => false],
            ['title' => 'Dessin', 'language' => false],
            ['title' => 'Calcul rapide', 'language' => false],
        ];

        if ($classroom->section === ClassroomSection::Francophone) {
            return array_merge($main, [['title' => 'Anglais', 'language' => true]]);
        }

        if ($classroom->section === ClassroomSection::Anglophone) {
            return array_merge([
                ['title' => 'English', 'language' => false],
                ['title' => 'Mathematics', 'language' => false],
                ['title' => 'Science', 'language' => false],
                ['title' => 'Social Studies', 'language' => false],
                ['title' => 'ICT', 'language' => false],
                ['title' => 'Art', 'language' => false],
            ], [['title' => 'Français', 'language' => true]]);
        }

        return array_merge($main, [
            ['title' => 'Anglais', 'language' => true],
            ['title' => 'Français', 'language' => true],
        ]);
    }

    private function dayForSubject(Classroom $classroom, string $subject): ?CourseDay
    {
        $entry = TimetableEntry::query()
            ->where('level', $classroom->level)
            ->where('section', $classroom->section?->value)
            ->where('subject', $subject)
            ->first();

        return $entry?->day;
    }
}
