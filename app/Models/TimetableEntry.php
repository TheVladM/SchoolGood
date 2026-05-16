<?php

namespace App\Models;

use App\Enums\ClassroomSection;
use App\Enums\CourseDay;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TimetableEntry extends Model
{
    use HasFactory;

    protected $fillable = [
        'level',
        'section',
        'subject',
        'day',
        'start_time',
        'end_time',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'section' => ClassroomSection::class,
            'day' => CourseDay::class,
        ];
    }
}
