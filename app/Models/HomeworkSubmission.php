<?php

namespace App\Models;

use App\Enums\HomeworkSubmissionStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class HomeworkSubmission extends Model
{
    protected $fillable = [
        'homework_id',
        'student_id',
        'status',
        'grade',
        'file_path',
        'teacher_feedback',
        'submitted_at',
        'graded_at',
    ];

    protected function casts(): array
    {
        return [
            'status' => HomeworkSubmissionStatus::class,
            'grade' => 'decimal:2',
            'submitted_at' => 'datetime',
            'graded_at' => 'datetime',
        ];
    }

    public function homework(): BelongsTo
    {
        return $this->belongsTo(Homework::class);
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }
}
