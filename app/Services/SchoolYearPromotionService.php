<?php

namespace App\Services;

use App\Enums\SchoolLevel;
use App\Enums\SchoolYearStatus;
use App\Enums\StudentSchoolYearStatus;
use App\Models\Classroom;
use App\Models\SchoolYear;
use App\Models\StudentSchoolYearRecord;
use Illuminate\Support\Facades\DB;

class SchoolYearPromotionService
{
    /**
     * @return array{prepared: int, skipped: int}
     */
    public function preparePromotions(SchoolYear $schoolYear): array
    {
        if (! $schoolYear->canPreparePromotions()) {
            return ['prepared' => 0, 'skipped' => 0];
        }

        $nextSchoolYear = $schoolYear->nextSchoolYear;

        if (! $nextSchoolYear) {
            return ['prepared' => 0, 'skipped' => 0];
        }

        return DB::transaction(function () use ($schoolYear, $nextSchoolYear): array {
            $prepared = 0;
            $skipped = 0;

            $records = $schoolYear->studentRecords()
                ->with(['student', 'classroom'])
                ->where('status', StudentSchoolYearStatus::Active->value)
                ->get();

            foreach ($records as $record) {
                if (! $record->student?->is_active || ! $record->classroom) {
                    $skipped++;

                    continue;
                }

                $nextLevel = SchoolLevel::nextLevel($record->level_snapshot ?? '');

                if (! $nextLevel) {
                    $skipped++;

                    continue;
                }

                $targetClassroom = $this->resolvePromotedClassroom($record->classroom, $nextLevel);

                if (! $targetClassroom) {
                    $skipped++;

                    continue;
                }

                if (StudentSchoolYearRecord::query()
                    ->where('student_id', $record->student_id)
                    ->where('school_year_id', $nextSchoolYear->id)
                    ->exists()) {
                    $skipped++;

                    continue;
                }

                StudentSchoolYearRecord::create([
                    'student_id' => $record->student_id,
                    'school_year_id' => $nextSchoolYear->id,
                    'classroom_id' => $targetClassroom->id,
                    'classroom_name_snapshot' => $targetClassroom->name,
                    'level_snapshot' => $targetClassroom->level,
                    'section_snapshot' => $targetClassroom->section?->value,
                    'status' => StudentSchoolYearStatus::PreRegistered,
                    'promoted_from_id' => $record->id,
                    'promoted_at' => now(),
                ]);

                $record->update([
                    'status' => StudentSchoolYearStatus::Promoted,
                    'promoted_at' => now(),
                ]);

                $record->student->update([
                    'classroom_id' => $targetClassroom->id,
                ]);

                $prepared++;
            }

            $schoolYear->update([
                'status' => SchoolYearStatus::Closed,
                'promoted_at' => now(),
            ]);

            return ['prepared' => $prepared, 'skipped' => $skipped];
        });
    }

    public function runDueAutoPromotions(): int
    {
        $years = SchoolYear::query()
            ->where('auto_promote_enabled', true)
            ->whereNull('promoted_at')
            ->whereNotNull('next_school_year_id')
            ->get();

        $total = 0;

        foreach ($years as $year) {
            if (! $year->canPreparePromotions()) {
                continue;
            }

            $result = $this->preparePromotions($year);
            $total += $result['prepared'];
        }

        return $total;
    }

    private function resolvePromotedClassroom(Classroom $currentClassroom, string $nextLevel): ?Classroom
    {
        $candidates = Classroom::query()
            ->where('level', $nextLevel)
            ->where('section', $currentClassroom->section?->value)
            ->orderBy('name')
            ->get();

        if ($candidates->isEmpty()) {
            return null;
        }

        if ($candidates->count() === 1) {
            return $candidates->first();
        }

        $suffix = trim(str_ireplace($currentClassroom->level, '', $currentClassroom->name));

        if (filled($suffix)) {
            $matched = $candidates->first(
                fn (Classroom $candidate) => str_ends_with(strtolower($candidate->name), strtolower($suffix))
            );

            if ($matched) {
                return $matched;
            }
        }

        return $candidates->first();
    }
}
