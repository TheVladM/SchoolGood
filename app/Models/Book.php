<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Book extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'author',
        'isbn',
        'category',
        'language',
        'total_copies',
        'shelf_location',
        'loan_duration_days',
        'late_fee_per_day',
        'description',
        'acquired_at',
        'managed_by_id',
    ];

    protected function casts(): array
    {
        return [
            'total_copies' => 'integer',
            'loan_duration_days' => 'integer',
            'late_fee_per_day' => 'decimal:2',
            'acquired_at' => 'date',
        ];
    }

    public function loans(): HasMany
    {
        return $this->hasMany(BookLoan::class);
    }

    public function activeLoans(): HasMany
    {
        return $this->hasMany(BookLoan::class)->whereNull('returned_at');
    }

    public function managedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'managed_by_id');
    }

    public function availableCopies(): int
    {
        $activeLoansCount = $this->active_loans_count
            ?? ($this->relationLoaded('activeLoans') ? $this->activeLoans->count() : $this->activeLoans()->count());

        return max(0, $this->total_copies - $activeLoansCount);
    }
}
