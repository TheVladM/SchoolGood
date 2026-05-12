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
}
