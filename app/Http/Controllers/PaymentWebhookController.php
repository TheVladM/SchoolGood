<?php

namespace App\Http\Controllers;

use App\Services\Payments\MtnMomoGateway;
use App\Services\Payments\OrangeMoneyGateway;
use App\Services\Payments\PaymentWebhookProcessor;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PaymentWebhookController extends Controller
{
    public function __construct(private PaymentWebhookProcessor $processor) {}

    public function orange(Request $request, OrangeMoneyGateway $gateway): JsonResponse
    {
        $raw = $request->getContent();
        $signature = $request->header('X-Signature') ?? $request->header('X-Orange-Signature');

        if (! $gateway->verifyWebhookSignature($raw, $signature)) {
            return response()->json(['message' => 'Invalid signature'], 401);
        }

        $payload = $request->all();
        if ($payload === [] && $raw !== '') {
            $payload = json_decode($raw, true) ?? [];
        }

        $event = $this->processor->process('orange', $payload, $signature, $raw);

        return response()->json(['status' => $event->processing_status]);
    }

    public function mtn(Request $request, MtnMomoGateway $gateway): JsonResponse
    {
        $raw = $request->getContent();
        $signature = $request->header('X-Signature') ?? $request->header('X-Callback-Signature');

        if (! $gateway->verifyWebhookSignature($raw, $signature)) {
            return response()->json(['message' => 'Invalid signature'], 401);
        }

        $payload = $request->all();
        if ($payload === [] && $raw !== '') {
            $payload = json_decode($raw, true) ?? [];
        }

        $event = $this->processor->process('mtn', $payload, $signature, $raw);

        return response()->json(['status' => $event->processing_status]);
    }
}
