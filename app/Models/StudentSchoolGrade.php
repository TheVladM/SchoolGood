<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StudentSchoolGrade extends Model
{
    protected $fillable = [
        'student_id',
        'school_year_id',
        'subject',
        'term',
        'grade',
        'comment',
        'recorded_by_id',
    ];

    protected function casts(): array
    {
        return [
            'grade' => 'decimal:2',
        ];
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function schoolYear(): BelongsTo
    {
        return $this->belongsTo(SchoolYear::class);
    }

    public function recordedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'recorded_by_id');
    }
}
