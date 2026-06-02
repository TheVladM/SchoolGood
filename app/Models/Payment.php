<?php

namespace App\Models;

use App\Enums\PaymentChannel;
use App\Enums\PaymentMethod;
use App\Enums\PaymentStatus;
use App\Enums\PaymentType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'type',
        'amount',
        'reference',
        'method',
        'account_reference',
        'status',
        'notes',
        'received_by_id',
        'validated_by_id',
        'validated_at',
        'declared_by_parent',
        'channel',
        'intent_reference',
        'operator_transaction_id',
        'operator_status',
        'payer_phone',
        'receipt_number',
        'paid_at',
    ];

    protected function casts(): array
    {
        return [
            'type' => PaymentType::class,
            'method' => PaymentMethod::class,
            'channel' => PaymentChannel::class,
            'status' => PaymentStatus::class,
            'amount' => 'decimal:2',
            'validated_at' => 'datetime',
            'paid_at' => 'datetime',
            'declared_by_parent' => 'boolean',
        ];
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function receivedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'received_by_id');
    }

    public function validatedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'validated_by_id');
    }
}
