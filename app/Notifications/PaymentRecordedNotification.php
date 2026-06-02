<?php

namespace App\Notifications;

use App\Models\Payment;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class PaymentRecordedNotification extends Notification
{
    use Queueable;

    public function __construct(public Payment $payment) {}

    /**
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'title' => 'Paiement enregistré',
            'message' => sprintf(
                'Un paiement de %s FCFA a été enregistré pour %s.',
                number_format((float) $this->payment->amount, 0, ',', ' '),
                $this->payment->student?->full_name ?? 'un élève'
            ),
            'url' => route('payments.show', $this->payment),
        ];
    }
}
