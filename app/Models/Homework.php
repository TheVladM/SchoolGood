<?php

namespace App\Models;

use App\Enums\HomeworkStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Homework extends Model
{
    use HasFactory;

    protected $table = 'homeworks';

    protected $fillable = [
        'title',
        'description',
        'subject',
        'teacher_id',
        'classroom_id',
        'due_date',
        'attachments',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'attachments' => 'array',
            'due_date' => 'datetime',
            'status' => HomeworkStatus::class,
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

    public function submissions(): HasMany
    {
        return $this->hasMany(HomeworkSubmission::class);
    }

    public function isOverdue(): bool
    {
        return now()->isAfter($this->due_date);
    }

    public function daysUntilDue(): int
    {
        return now()->diffInDays($this->due_date, false);
    }
}
