<?php

namespace App\Models;

use App\Enums\ClassroomSection;
use App\Enums\StudentSchoolYearStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class StudentSchoolYearRecord extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'school_year_id',
        'classroom_id',
        'classroom_name_snapshot',
        'level_snapshot',
        'section_snapshot',
        'status',
        'final_average',
        'final_result',
        'remarks',
        'promoted_from_id',
        'promoted_at',
        'withdrawn_at',
    ];

    protected function casts(): array
    {
        return [
            'status' => StudentSchoolYearStatus::class,
            'section_snapshot' => ClassroomSection::class,
            'final_average' => 'decimal:2',
            'promoted_at' => 'datetime',
            'withdrawn_at' => 'datetime',
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

    public function classroom(): BelongsTo
    {
        return $this->belongsTo(Classroom::class);
    }

    public function promotedFrom(): BelongsTo
    {
        return $this->belongsTo(self::class, 'promoted_from_id');
    }

    public function promotedTo(): HasMany
    {
        return $this->hasMany(self::class, 'promoted_from_id');
    }
}
