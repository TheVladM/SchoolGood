<?php

namespace App\Models;

use App\Enums\SchoolYearStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SchoolYear extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'starts_on',
        'ends_on',
        'diploma_awarded_on',
        'promotion_opens_on',
        'status',
        'next_school_year_id',
        'promoted_at',
    ];

    protected function casts(): array
    {
        return [
            'starts_on' => 'date',
            'ends_on' => 'date',
            'diploma_awarded_on' => 'date',
            'promotion_opens_on' => 'date',
            'promoted_at' => 'datetime',
            'status' => SchoolYearStatus::class,
        ];
    }

    public function studentRecords(): HasMany
    {
        return $this->hasMany(StudentSchoolYearRecord::class);
    }

    public function nextSchoolYear(): BelongsTo
    {
        return $this->belongsTo(self::class, 'next_school_year_id');
    }

    public function canPreparePromotions(): bool
    {
        $threshold = $this->promotion_opens_on ?? $this->diploma_awarded_on ?? $this->ends_on;

        return $threshold !== null && now()->startOfDay()->greaterThanOrEqualTo($threshold->startOfDay());
    }
}
