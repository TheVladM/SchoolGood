<?php

namespace App\Models;

use App\Enums\ClassroomCycleType;
use App\Enums\ClassroomSection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Classroom extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'level',
        'section',
        'room',
        'location',
        'room_id',
        'cycle_type',
        'main_teacher_id',
        'language_teacher_id',
    ];

    protected function casts(): array
    {
        return [
            'section' => ClassroomSection::class,
            'cycle_type' => ClassroomCycleType::class,
        ];
    }

    public function schoolRoom(): BelongsTo
    {
        return $this->belongsTo(Room::class, 'room_id');
    }

    public function students(): HasMany
    {
        return $this->hasMany(Student::class);
    }

    public function courses(): HasMany
    {
        return $this->hasMany(Course::class);
    }

    public function mainTeacher(): BelongsTo
    {
        return $this->belongsTo(User::class, 'main_teacher_id');
    }

    public function languageTeacher(): BelongsTo
    {
        return $this->belongsTo(User::class, 'language_teacher_id');
    }

    public function announcements(): HasMany
    {
        return $this->hasMany(Announcement::class);
    }

    public function timetableEntries(): HasMany
    {
        return $this->hasMany(TimetableEntry::class, 'level', 'level')
            ->where('section', $this->section?->value)
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

    /**
     * Check if the classroom has a valid teacher configuration.
     * For levels SIL to CM2, there should be both main and language teachers.
     */
    public function hasValidTeacherConfiguration(): bool
    {
        // Levels that require two teachers
        $multiTeacherLevels = ['SIL', 'CP', 'CE1', 'CE2', 'CM1', 'CM2'];

        if (in_array($this->level, $multiTeacherLevels)) {
            return $this->main_teacher_id && $this->language_teacher_id && 
                   $this->main_teacher_id !== $this->language_teacher_id;
        }

        return true;
    }

    /**
     * Get the related classroom with opposite language (francophone <-> anglophone)
     */
    public function getRelatedLanguageClassroom(): ?self
    {
        // For a francophone classroom teaching anglais, find the corresponding anglophone class
        // or vice versa
        $oppositeSection = $this->section === ClassroomSection::Francophone 
            ? ClassroomSection::Anglophone 
            : ClassroomSection::Francophone;

        return static::where('level', $this->level)
            ->where('section', $oppositeSection)
            ->first();
    }
}
