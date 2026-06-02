<?php

namespace App\Models;

use App\Enums\CourseDay;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Course extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'content',
        'teacher_id',
        'classroom_id',
        'timetable_entry_id',
        'day',
    ];

    protected function casts(): array
    {
        return [
            'day' => CourseDay::class,
        ];
    }

    public function teacher(): BelongsTo
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }

    public function classroom(): BelongsTo
    {
        return $this->belongsTo(Classroom::class);
    }

    public function timetableEntry(): BelongsTo
    {
        return $this->belongsTo(TimetableEntry::class);
    }
}
