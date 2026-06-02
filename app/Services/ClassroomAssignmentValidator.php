<?php

namespace App\Services;

use App\Enums\ClassroomSection;
use App\Enums\SchoolLevel;
use App\Models\Classroom;
use Illuminate\Validation\ValidationException;

class ClassroomAssignmentValidator
{
    public function validate(array $data, ?Classroom $classroom = null): void
    {
        if (! SchoolLevel::isValidForSection($data['level'], $data['section'])) {
            throw ValidationException::withMessages([
                'level' => 'Ce niveau n\'est pas valide pour la section choisie.',
            ]);
        }

        if (filled($data['main_teacher_id'] ?? null)) {
            $this->guardSingleTitular((int) $data['main_teacher_id'], $classroom);
            $this->guardCrossLanguageTitular((int) $data['main_teacher_id'], $data['section'], $classroom);
        }
    }

    private function guardSingleTitular(int $teacherId, ?Classroom $classroom): void
    {
        $exists = Classroom::query()
            ->where('main_teacher_id', $teacherId)
            ->when($classroom, fn ($q) => $q->whereKeyNot($classroom->id))
            ->exists();

        if ($exists) {
            throw ValidationException::withMessages([
                'main_teacher_id' => 'Un enseignant ne peut être titulaire que d\'une seule classe.',
            ]);
        }
    }

    private function guardCrossLanguageTitular(int $teacherId, string $section, ?Classroom $classroom): void
    {
        if ($section === ClassroomSection::Anglophone->value) {
            $teachesInFrancophone = Classroom::query()
                ->where('language_teacher_id', $teacherId)
                ->where('section', ClassroomSection::Francophone->value)
                ->when($classroom, fn ($q) => $q->whereKeyNot($classroom->id))
                ->exists();

            if ($teachesInFrancophone) {
                $titularAnglophone = Classroom::query()
                    ->where('main_teacher_id', $teacherId)
                    ->where('section', ClassroomSection::Anglophone->value)
                    ->when($classroom, fn ($q) => $q->whereKeyNot($classroom->id))
                    ->exists();

                if ($titularAnglophone) {
                    throw ValidationException::withMessages([
                        'main_teacher_id' => 'Un enseignant d\'anglais en section francophone ne peut être titulaire que d\'une seule classe anglophone.',
                    ]);
                }
            }
        }

        if ($section === ClassroomSection::Francophone->value) {
            $teachesInAnglophone = Classroom::query()
                ->where('language_teacher_id', $teacherId)
                ->where('section', ClassroomSection::Anglophone->value)
                ->when($classroom, fn ($q) => $q->whereKeyNot($classroom->id))
                ->exists();

            if ($teachesInAnglophone) {
                $titularFrancophone = Classroom::query()
                    ->where('main_teacher_id', $teacherId)
                    ->where('section', ClassroomSection::Francophone->value)
                    ->when($classroom, fn ($q) => $q->whereKeyNot($classroom->id))
                    ->exists();

                if ($titularFrancophone) {
                    throw ValidationException::withMessages([
                        'main_teacher_id' => 'Un enseignant de français en section anglophone ne peut être titulaire que d\'une seule classe francophone.',
                    ]);
                }
            }
        }
    }
}
