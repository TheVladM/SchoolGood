<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BookLoan extends Model
{
    use HasFactory;

    protected $fillable = [
        'book_id',
        'student_id',
        'user_id',
        'borrowed_at',
        'due_at',
        'returned_at',
        'daily_penalty_rate',
        'notes',
        'issued_by_id',
        'returned_by_id',
    ];

    protected function casts(): array
    {
        return [
            'borrowed_at' => 'date',
            'due_at' => 'date',
            'returned_at' => 'date',
            'daily_penalty_rate' => 'decimal:2',
        ];
    }

    public function book(): BelongsTo
    {
        return $this->belongsTo(Book::class);
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function issuedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'issued_by_id');
    }

    public function returnedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'returned_by_id');
    }

    protected function borrowerName(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->student?->full_name ?? $this->user?->name ?? 'Emprunteur inconnu'
        );
    }

    public function overdueDays(): int
    {
        $returnDate = $this->returned_at ?? now()->toDateString();

        if (! $this->due_at || $returnDate <= $this->due_at->toDateString()) {
            return 0;
        }

        return $this->due_at->diffInDays($returnDate);
    }

    public function penaltyAmount(): float
    {
        return $this->overdueDays() * (float) ($this->daily_penalty_rate ?? 0);
    }
}
