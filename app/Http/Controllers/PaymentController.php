<?php

namespace App\Http\Controllers;

use App\Enums\PaymentChannel;
use App\Enums\PaymentMethod;
use App\Enums\PaymentStatus;
use App\Enums\PaymentType;
use App\Enums\UserRole;
use App\Models\Payment;
use App\Models\Student;
use App\Models\User;
use App\Notifications\PaymentRecordedNotification;
use App\Services\PaymentReceiptService;
use App\Services\Sms\SmsService;
use App\Services\StudentTuitionService;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class PaymentController extends Controller
{
    public function __construct(
        private StudentTuitionService $tuitionService,
        private PaymentReceiptService $receipts,
        private SmsService $sms,
    ) {}

    public function index(Request $request): View
    {
        $this->authorize('viewAny', Payment::class);

        $payments = $this->visiblePaymentsQuery($request->user())
            ->with(['student.classroom'])
            ->latest()
            ->paginate(10);

        $pendingValidationCount = 0;

        if ($request->user()->hasRole(UserRole::Founder)) {
            $pendingValidationCount = Payment::query()
                ->where('status', PaymentStatus::Pending->value)
                ->count();
        }

        return view('payments.index', [
            'payments' => $payments,
            'pendingValidationCount' => $pendingValidationCount,
        ]);
    }

    public function create(Request $request): View
    {
        $this->authorize('create', Payment::class);

        return view('payments.create', [
            'students' => Student::with('classroom')->orderBy('last_name')->get(),
            'types' => PaymentType::options(),
            'methods' => PaymentMethod::options(),
            'statuses' => PaymentStatus::options(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $this->authorize('create', Payment::class);

        $payment = Payment::create($this->normalizedPaymentData($request->user(), $this->validatedData($request)));

        $payment->load('student.parent');
        if ($payment->student?->parent) {
            $payment->student->parent->notify(new PaymentRecordedNotification($payment));
        }

        return redirect()
            ->route('payments.index')
            ->with('success', 'Paiement enregistré avec succès.');
    }

    public function show(Request $request, Payment $payment): View
    {
        $this->authorize('view', $payment);
        $payment->load(['student.classroom', 'student.parent', 'receivedBy', 'validatedBy']);

        $installmentBreakdown = $payment->student
            ? $this->tuitionService->installmentBreakdown($payment->student)
            : [];

        return view('payments.show', [
            'payment' => $payment,
            'installmentBreakdown' => $installmentBreakdown,
            'balanceDue' => $payment->student ? $this->tuitionService->balanceDue($payment->student) : 0,
        ]);
    }

    public function edit(Request $request, Payment $payment): View
    {
        $this->authorize('update', $payment);

        return view('payments.edit', [
            'payment' => $payment,
            'students' => Student::with('classroom')->orderBy('last_name')->get(),
            'types' => PaymentType::options(),
            'methods' => PaymentMethod::options(),
            'statuses' => PaymentStatus::options(),
        ]);
    }

    public function update(Request $request, Payment $payment): RedirectResponse
    {
        $this->authorize('update', $payment);

        $payment->update($this->normalizedPaymentData($request->user(), $this->validatedData($request), $payment));

        return redirect()
            ->route('payments.index')
            ->with('success', 'Paiement mis à jour avec succès.');
    }

    public function destroy(Request $request, Payment $payment): RedirectResponse
    {
        $this->authorize('delete', $payment);

        $payment->delete();

        return redirect()
            ->route('payments.index')
            ->with('success', 'Paiement supprimé avec succès.');
    }

    public function validatePayment(Request $request, Payment $payment): RedirectResponse
    {
        $this->authorize('validate', $payment);

        $payment->update([
            'status' => PaymentStatus::Paid,
            'validated_by_id' => $request->user()->id,
            'validated_at' => now(),
            'paid_at' => now(),
        ]);

        $this->receipts->assignReceiptNumber($payment);
        $payment->refresh()->load('student.parent');

        if ($payment->student?->parent) {
            $payment->student->parent->notify(new PaymentRecordedNotification($payment));
            if ($payment->student->parent->phone) {
                $this->sms->send(
                    $payment->student->parent->phone,
                    sprintf(
                        'SchoolGood : paiement validé (%s FCFA). Reçu %s.',
                        number_format((float) $payment->amount, 0, ',', ' '),
                        $payment->receipt_number ?? ''
                    )
                );
            }
        }

        return back()->with('success', 'Paiement validé.');
    }

    public function declareForm(Request $request): View
    {
        $this->authorize('declare', Payment::class);

        return view('payments.declare', [
            'children' => $request->user()->children()->with('classroom')->get(),
            'types' => PaymentType::options(),
            'methods' => PaymentMethod::options(),
            'accounts' => config('school.payment_accounts'),
        ]);
    }

    public function declareStore(Request $request): RedirectResponse
    {
        $this->authorize('declare', Payment::class);

        $data = $request->validate([
            'student_id' => ['required', 'exists:students,id'],
            'type' => ['required', Rule::enum(PaymentType::class)],
            'amount' => ['required', 'numeric', 'min:0'],
            'method' => ['required', Rule::enum(PaymentMethod::class)],
            'reference' => ['nullable', 'string', 'max:255'],
            'account_reference' => ['nullable', 'string', 'max:255'],
            'notes' => ['nullable', 'string'],
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

        Payment::create([
            ...$data,
            'channel' => PaymentChannel::Declared,
            'status' => PaymentStatus::Pending,
            'declared_by_parent' => true,
            'received_by_id' => null,
        ]);

        return redirect()
            ->route('payments.index')
            ->with('success', 'Déclaration envoyée. La scolarité validera votre paiement.');
    }

    public function tuitionSummary(Request $request, Student $student): JsonResponse
    {
        $this->authorize('create', Payment::class);

        $type = $request->query('type');

        return response()->json([
            'expected_amount' => $type ? $this->tuitionService->expectedAmount($student, $type) : null,
            'balance_due' => $this->tuitionService->balanceDue($student),
            'total_annual_due' => $this->tuitionService->totalAnnualDue($student),
            'total_paid' => $this->tuitionService->totalPaid($student),
            'installments' => $this->tuitionService->installmentBreakdown($student),
        ]);
    }

    private function validatedData(Request $request): array
    {
        $data = $request->validate([
            'student_id' => ['required', 'exists:students,id'],
            'type' => ['required', Rule::enum(PaymentType::class)],
            'amount' => ['required', 'numeric', 'min:0'],
            'reference' => ['nullable', 'string', 'max:255'],
            'method' => ['required', Rule::enum(PaymentMethod::class)],
            'account_reference' => ['nullable', 'string', 'max:255'],
            'status' => ['required', Rule::enum(PaymentStatus::class)],
            'notes' => ['nullable', 'string'],
        ]);

        if (! $request->filled('amount') || (float) $data['amount'] === 0.0) {
            $student = Student::with('classroom')->find($data['student_id']);
            $expected = $student ? $this->tuitionService->expectedAmount($student, $data['type']) : null;

            if ($expected !== null) {
                $data['amount'] = $expected;
            }
        }

        return $data;
    }

    private function visiblePaymentsQuery(User $user): Builder
    {
        if ($user->hasRole(UserRole::Parent)) {
            return Payment::query()->whereHas('student', fn ($query) => $query->where('parent_id', $user->id));
        }

        return Payment::query();
    }

    private function normalizedPaymentData(User $user, array $data, ?Payment $payment = null): array
    {
        $data['channel'] = $payment?->channel?->value ?? PaymentChannel::Manual->value;
        $data['received_by_id'] = $payment?->received_by_id ?? $user->id;

        if ($data['status'] === PaymentStatus::Paid->value) {
            $data['validated_by_id'] = $payment?->validated_by_id ?? $user->id;
            $data['validated_at'] = $payment?->validated_at ?? now();

            return $data;
        }

        $data['validated_by_id'] = null;
        $data['validated_at'] = null;

        return $data;
    }
}
