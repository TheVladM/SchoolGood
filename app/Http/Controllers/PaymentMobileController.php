<?php

namespace App\Http\Controllers;

use App\Enums\PaymentChannel;
use App\Enums\PaymentMethod;
use App\Enums\PaymentStatus;
use App\Enums\PaymentType;
use App\Models\Payment;
use App\Models\Student;
use App\Services\Payments\PaymentGatewayManager;
use App\Services\Payments\PaymentIntentReference;
use App\Services\StudentTuitionService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class PaymentMobileController extends Controller
{
    public function __construct(
        private StudentTuitionService $tuitionService,
        private PaymentGatewayManager $gateways,
    ) {}

    public function create(Request $request): View
    {
        $this->authorize('declare', Payment::class);

        return view('payments.pay', [
            'children' => $request->user()->children()->with('classroom')->get(),
            'types' => PaymentType::options(),
            'methods' => [
                PaymentMethod::OrangeMoney->value => PaymentMethod::OrangeMoney->label(),
                PaymentMethod::MtnMomo->value => PaymentMethod::MtnMomo->label(),
            ],
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $this->authorize('declare', Payment::class);

        $data = $request->validate([
            'student_id' => ['required', 'exists:students,id'],
            'type' => ['required', Rule::enum(PaymentType::class)],
            'amount' => ['required', 'numeric', 'min:1'],
            'method' => ['required', Rule::in([
                PaymentMethod::OrangeMoney->value,
                PaymentMethod::MtnMomo->value,
            ])],
            'payer_phone' => ['required', 'string', 'max:32'],
        ]);

        abort_unless(
            $request->user()->children()->whereKey($data['student_id'])->exists(),
            403
        );

        $student = Student::findOrFail($data['student_id']);
        if ((float) $data['amount'] === 0.0) {
            $expected = $this->tuitionService->expectedAmount($student, $data['type']);
            if ($expected !== null) {
                $data['amount'] = $expected;
            }
        }

        $method = PaymentMethod::from($data['method']);
        $channel = $method === PaymentMethod::OrangeMoney
            ? PaymentChannel::OrangeMoney
            : PaymentChannel::MtnMomo;

        $intentReference = PaymentIntentReference::generate();

        $payment = Payment::create([
            'student_id' => $data['student_id'],
            'type' => $data['type'],
            'amount' => $data['amount'],
            'method' => $method,
            'channel' => $channel,
            'status' => PaymentStatus::Pending,
            'intent_reference' => $intentReference,
            'payer_phone' => $data['payer_phone'],
            'declared_by_parent' => true,
            'reference' => $intentReference,
        ]);

        $result = $this->gateways->gateway($method)->initiate($payment, $data['payer_phone']);

        if (! $result->success) {
            $payment->update(['operator_status' => 'FAILED', 'notes' => $result->message]);

            return back()->withInput()->withErrors(['method' => $result->message ?? 'Échec du paiement mobile.']);
        }

        if ($result->operatorReference) {
            $payment->update([
                'operator_transaction_id' => $payment->operator_transaction_id ?? $result->operatorReference,
            ]);
        }

        if ($result->redirectUrl) {
            return str_starts_with($result->redirectUrl, 'http')
                ? redirect()->away($result->redirectUrl)
                : redirect($result->redirectUrl);
        }

        return redirect()
            ->route('payments.mobile.pending', $payment)
            ->with('success', $result->message ?? 'Paiement initié.');
    }

    public function pending(Request $request, Payment $payment): View
    {
        $this->authorize('view', $payment);

        return view('payments.pending', ['payment' => $payment]);
    }

    public function return(Request $request, Payment $payment): RedirectResponse
    {
        $this->authorize('view', $payment);

        return redirect()
            ->route('payments.mobile.pending', $payment)
            ->with('success', 'Merci. Votre paiement sera confirmé dès réception de la notification opérateur.');
    }
}
