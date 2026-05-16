<?php

namespace App\Models;

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
        'main_teacher_id',
        'language_teacher_id',
    ];

    protected function casts(): array
    {
        return [
            'section' => ClassroomSection::class,
        ];
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
}
