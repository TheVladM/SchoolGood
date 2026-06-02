<?php

namespace App\Services\Payments;

use App\Enums\PaymentChannel;
use App\Enums\PaymentStatus;
use App\Models\Payment;
use App\Models\PaymentWebhookEvent;
use App\Notifications\PaymentRecordedNotification;
use App\Services\PaymentReceiptService;
use App\Services\Sms\SmsService;
use Illuminate\Support\Facades\DB;

class PaymentWebhookProcessor
{
    public function __construct(
        private PaymentReceiptService $receipts,
        private SmsService $sms,
    ) {}

    /**
     * @param  array<string, mixed>  $payload
     */
    public function process(string $provider, array $payload, ?string $signature, string $rawPayload): PaymentWebhookEvent
    {
        $eventId = (string) (data_get($payload, 'event_id')
            ?? data_get($payload, 'transaction_id')
            ?? data_get($payload, 'financialTransactionId')
            ?? data_get($payload, 'txnid')
            ?? '');

        $event = PaymentWebhookEvent::create([
            'provider' => $provider,
            'event_id' => $eventId ?: null,
            'payload' => $payload,
            'signature' => $signature,
            'processing_status' => 'received',
        ]);

        try {
            $payment = $this->resolvePayment($provider, $payload);
            if (! $payment) {
                $event->update([
                    'processing_status' => 'ignored',
                    'error_message' => 'Paiement introuvable pour cette notification.',
                    'processed_at' => now(),
                ]);

                return $event;
            }

            $event->update(['payment_id' => $payment->id]);

            if ($this->isSuccessStatus($provider, $payload)) {
                $this->markPaid($payment, $payload, $provider);
                $event->update(['processing_status' => 'processed', 'processed_at' => now()]);
            } else {
                $payment->update([
                    'operator_status' => $this->extractStatus($provider, $payload),
                ]);
                $event->update(['processing_status' => 'acknowledged', 'processed_at' => now()]);
            }
        } catch (\Throwable $exception) {
            $event->update([
                'processing_status' => 'failed',
                'error_message' => $exception->getMessage(),
                'processed_at' => now(),
            ]);
        }

        return $event;
    }

    /**
     * @param  array<string, mixed>  $payload
     */
    private function resolvePayment(string $provider, array $payload): ?Payment
    {
        $intent = data_get($payload, 'order_id')
            ?? data_get($payload, 'externalId')
            ?? data_get($payload, 'payeeNote')
            ?? data_get($payload, 'reference');

        if ($intent) {
            $payment = Payment::where('intent_reference', $intent)->first();
            if ($payment) {
                return $payment;
            }
        }

        $operatorId = data_get($payload, 'transaction_id')
            ?? data_get($payload, 'financialTransactionId')
            ?? data_get($payload, 'txnid');

        if ($operatorId) {
            return Payment::where('operator_transaction_id', $operatorId)->first();
        }

        return null;
    }

    /**
     * @param  array<string, mixed>  $payload
     */
    private function isSuccessStatus(string $provider, array $payload): bool
    {
        $status = strtoupper((string) $this->extractStatus($provider, $payload));

        return in_array($status, [
            'SUCCESS',
            'SUCCESSFUL',
            'COMPLETED',
            'PAID',
            'SUCCESSFULL',
        ], true);
    }

    /**
     * @param  array<string, mixed>  $payload
     */
    private function extractStatus(string $provider, array $payload): string
    {
        return (string) (
            data_get($payload, 'status')
            ?? data_get($payload, 'payment_status')
            ?? data_get($payload, 'Status')
            ?? ''
        );
    }

    /**
     * @param  array<string, mixed>  $payload
     */
    private function markPaid(Payment $payment, array $payload, string $provider): void
    {
        if ($payment->status === PaymentStatus::Paid) {
            return;
        }

        DB::transaction(function () use ($payment, $payload, $provider): void {
            $payment->update([
                'status' => PaymentStatus::Paid,
                'operator_status' => $this->extractStatus($provider, $payload),
                'operator_transaction_id' => $payment->operator_transaction_id
                    ?: (string) (data_get($payload, 'transaction_id')
                        ?? data_get($payload, 'financialTransactionId')
                        ?? data_get($payload, 'txnid')),
                'paid_at' => now(),
                'validated_at' => now(),
                'channel' => $provider === 'orange' ? PaymentChannel::OrangeMoney : PaymentChannel::MtnMomo,
            ]);

            $this->receipts->assignReceiptNumber($payment);
        });

        $payment->load('student.parent');
        $payment->student?->parent?->notify(new PaymentRecordedNotification($payment));

        $parentPhone = $payment->student?->parent?->phone;
        if ($parentPhone) {
            $this->sms->send(
                $parentPhone,
                sprintf(
                    'SchoolGood : paiement de %s FCFA confirmé (%s). Reçu : %s',
                    number_format((float) $payment->amount, 0, ',', ' '),
                    $payment->intent_reference,
                    $payment->receipt_number ?? '—'
                )
            );
        }
    }
}
