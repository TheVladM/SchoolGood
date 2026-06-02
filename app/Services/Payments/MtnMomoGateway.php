<?php

namespace App\Services\Payments;

use App\Models\Payment;
use App\Services\Payments\Contracts\MobilePaymentGateway;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class MtnMomoGateway implements MobilePaymentGateway
{
    public function initiate(Payment $payment, string $payerPhone): PaymentInitiationResult
    {
        if (! config('payments.mtn.enabled')) {
            return $this->simulateInitiation($payment);
        }

        $referenceId = (string) Str::uuid();
        $token = $this->collectionToken();
        if (! $token) {
            return new PaymentInitiationResult(false, message: 'Impossible de contacter MTN MoMo.');
        }

        $callbackUrl = config('payments.mtn.callback_url') ?: route('webhooks.payments.mtn');
        $baseUrl = rtrim((string) config('payments.mtn.base_url'), '/');

        $response = Http::withToken($token)
            ->withHeaders([
                'X-Reference-Id' => $referenceId,
                'X-Target-Environment' => config('payments.mtn.target_environment', 'sandbox'),
                'Ocp-Apim-Subscription-Key' => config('payments.mtn.subscription_key'),
            ])
            ->post("{$baseUrl}/collection/v1_0/requesttopay", [
                'amount' => (string) round((float) $payment->amount),
                'currency' => config('payments.mtn.currency', 'XAF'),
                'externalId' => $payment->intent_reference,
                'payer' => [
                    'partyIdType' => 'MSISDN',
                    'partyId' => $this->normalizePhone($payerPhone),
                ],
                'payerMessage' => 'Scolarité '.$payment->student?->full_name,
                'payeeNote' => $payment->intent_reference,
            ]);

        if (! in_array($response->status(), [200, 202], true)) {
            Log::warning('MTN MoMo initiation failed', ['status' => $response->status(), 'body' => $response->body()]);

            return new PaymentInitiationResult(false, message: 'MTN MoMo a refusé la demande. Vérifiez le numéro et le solde.');
        }

        $payment->update([
            'operator_transaction_id' => $referenceId,
            'operator_status' => 'PENDING',
        ]);

        return new PaymentInitiationResult(
            true,
            redirectUrl: route('payments.mobile.pending', $payment),
            operatorReference: $referenceId,
            message: 'Validez le paiement sur votre téléphone MTN.',
        );
    }

    public function verifyWebhookSignature(string $payload, ?string $signature): bool
    {
        $secret = config('payments.mtn.webhook_secret');
        if (! $secret) {
            return config('payments.simulate_webhooks');
        }

        if (! $signature) {
            return false;
        }

        return hash_equals(hash_hmac('sha256', $payload, $secret), $signature);
    }

    private function collectionToken(): ?string
    {
        $baseUrl = rtrim((string) config('payments.mtn.base_url'), '/');

        $response = Http::withBasicAuth(
            (string) config('payments.mtn.api_user'),
            (string) config('payments.mtn.api_key')
        )
            ->withHeaders([
                'Ocp-Apim-Subscription-Key' => config('payments.mtn.subscription_key'),
            ])
            ->post("{$baseUrl}/collection/token/");

        if (! $response->successful()) {
            return null;
        }

        return $response->json('access_token');
    }

    private function normalizePhone(string $phone): string
    {
        $digits = preg_replace('/\D+/', '', $phone) ?? '';
        $code = (string) config('sms.default_country_code', '237');

        if (str_starts_with($digits, $code)) {
            return $digits;
        }

        if (str_starts_with($digits, '0')) {
            return $code.ltrim($digits, '0');
        }

        return $code.$digits;
    }

    private function simulateInitiation(Payment $payment): PaymentInitiationResult
    {
        $payment->update([
            'operator_status' => 'PENDING',
            'operator_transaction_id' => (string) Str::uuid(),
        ]);

        return new PaymentInitiationResult(
            true,
            redirectUrl: route('payments.mobile.pending', $payment),
            message: 'Mode simulation MTN : confirmez via webhook de test.',
        );
    }
}
