<?php

namespace App\Services\Payments;

use App\Models\Payment;
use App\Services\Payments\Contracts\MobilePaymentGateway;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
class OrangeMoneyGateway implements MobilePaymentGateway
{
    public function initiate(Payment $payment, string $payerPhone): PaymentInitiationResult
    {
        if (! config('payments.orange.enabled')) {
            return $this->simulateInitiation($payment);
        }

        $token = $this->accessToken();
        if (! $token) {
            return new PaymentInitiationResult(false, message: 'Impossible de contacter Orange Money.');
        }

        $notifUrl = config('payments.orange.notif_url') ?: route('webhooks.payments.orange');
        $returnUrl = config('payments.orange.return_url') ?: route('payments.mobile.return', $payment);
        $cancelUrl = config('payments.orange.cancel_url') ?: route('payments.declare');

        $response = Http::withToken($token)
            ->acceptJson()
            ->post(config('payments.orange.payment_url'), [
                'merchant_key' => config('payments.orange.merchant_key'),
                'currency' => config('payments.orange.currency', 'XAF'),
                'order_id' => $payment->intent_reference,
                'amount' => (int) round((float) $payment->amount),
                'return_url' => $returnUrl,
                'cancel_url' => $cancelUrl,
                'notif_url' => $notifUrl,
                'lang' => 'fr',
                'reference' => config('school.payment_accounts.orange_money.name', 'SchoolGood'),
            ]);

        if (! $response->successful()) {
            Log::warning('Orange Money initiation failed', ['body' => $response->json(), 'status' => $response->status()]);

            return new PaymentInitiationResult(false, message: 'Orange Money a refusé la demande de paiement.');
        }

        $body = $response->json();
        $payToken = data_get($body, 'pay_token') ?? data_get($body, 'data.pay_token');
        $paymentUrl = data_get($body, 'payment_url') ?? data_get($body, 'data.payment_url');

        if ($paymentUrl) {
            return new PaymentInitiationResult(true, redirectUrl: $paymentUrl, operatorReference: $payToken);
        }

        return new PaymentInitiationResult(false, message: 'Réponse Orange Money incomplète.');
    }

    public function verifyWebhookSignature(string $payload, ?string $signature): bool
    {
        $secret = config('payments.orange.webhook_secret');
        if (! $secret) {
            return config('payments.simulate_webhooks');
        }

        if (! $signature) {
            return false;
        }

        $expected = hash_hmac('sha256', $payload, $secret);

        return hash_equals($expected, $signature);
    }

    private function accessToken(): ?string
    {
        $response = Http::asForm()
            ->withBasicAuth(
                (string) config('payments.orange.client_id'),
                (string) config('payments.orange.client_secret')
            )
            ->post(config('payments.orange.oauth_url'), [
                'grant_type' => 'client_credentials',
            ]);

        if (! $response->successful()) {
            return null;
        }

        return $response->json('access_token');
    }

    private function simulateInitiation(Payment $payment): PaymentInitiationResult
    {
        $payment->update([
            'operator_status' => 'INITIATED',
            'notes' => trim(($payment->notes ?? '')."\n[Simulation Orange] En attente du webhook opérateur."),
        ]);

        return new PaymentInitiationResult(
            true,
            redirectUrl: route('payments.mobile.pending', $payment),
            message: 'Mode simulation : validez via le webhook de test ou attendez la notification.',
        );
    }
}
