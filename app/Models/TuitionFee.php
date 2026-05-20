<?php

namespace App\Models;

use App\Enums\ClassroomSection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TuitionFee extends Model
{
    use HasFactory;

    protected $table = 'tuition_fees';

    protected $fillable = [
        'level',
        'section',
        'registration_fee',
        'first_installment',
        'second_installment',
        'third_installment',
        'notes',
        'managed_by_id',
    ];

    protected function casts(): array
    {
        return [
            'section' => ClassroomSection::class,
            'registration_fee' => 'decimal:2',
            'first_installment' => 'decimal:2',
            'second_installment' => 'decimal:2',
            'third_installment' => 'decimal:2',
        ];
    }

    public function managedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'managed_by_id');
    }

    /**
     * Get the total annual fees for this tuition level.
     */
    public function totalAnnualFees(): float
    {
        return (float) ($this->registration_fee +
            $this->first_installment +
            $this->second_installment +
            $this->third_installment);
    }

    /**
     * Get fee for a specific type.
     */
    public function getFeeByType(string $type): float
    {
        return match ($type) {
            'registration' => (float) $this->registration_fee,
            'first_installment' => (float) $this->first_installment,
            'second_installment' => (float) $this->second_installment,
            'third_installment' => (float) $this->third_installment,
            default => 0,
        };
    }
}
