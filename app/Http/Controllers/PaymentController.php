<?php

namespace App\Http\Controllers;

use App\Enums\PaymentMethod;
use App\Enums\PaymentStatus;
use App\Enums\PaymentType;
use App\Enums\UserRole;
use App\Models\Payment;
use App\Models\Student;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class PaymentController extends Controller
{
    public function index(Request $request): View
    {
        // Seul les rôles autorisés peuvent voir les paiements
        abort_unless(
            in_array($request->user()->role->value, [
                UserRole::Founder->value,
                UserRole::Scolarite->value,
                UserRole::Parent->value,
            ]),
            403
        );

        $payments = $this->visiblePaymentsQuery($request->user())
            ->with(['student.classroom'])
            ->latest()
            ->paginate(10);

        return view('payments.index', ['payments' => $payments]);
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

        Payment::create($this->normalizedPaymentData($request->user(), $this->validatedData($request)));

        return redirect()
            ->route('payments.index')
            ->with('success', 'Paiement enregistre avec succes.');
    }

    public function show(Request $request, Payment $payment): View
    {
        $this->authorize('view', $payment);
        $payment->load(['student.classroom', 'student.parent', 'receivedBy', 'validatedBy']);

        return view('payments.show', ['payment' => $payment]);
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
            ->with('success', 'Paiement mis a jour avec succes.');
    }

    public function destroy(Request $request, Payment $payment): RedirectResponse
    {
        $this->authorize('delete', $payment);

        $payment->delete();

        return redirect()
            ->route('payments.index')
            ->with('success', 'Paiement supprime avec succes.');
    }

    private function validatedData(Request $request): array
    {
        return $request->validate([
            'student_id' => ['required', 'exists:students,id'],
            'type' => ['required', Rule::enum(PaymentType::class)],
            'amount' => ['required', 'numeric', 'min:0'],
            'reference' => ['nullable', 'string', 'max:255'],
            'method' => ['required', Rule::enum(PaymentMethod::class)],
            'account_reference' => ['nullable', 'string', 'max:255'],
            'status' => ['required', Rule::enum(PaymentStatus::class)],
            'notes' => ['nullable', 'string'],
        ]);
    }

    private function authorizePaymentAccess(User $user): void
    {
        $this->authorizeRoles($user, [
            UserRole::Founder,
            UserRole::Scolarite,
            UserRole::Parent,
        ]);
    }

    private function authorizePaymentManagement(User $user): void
    {
        $this->authorizeRoles($user, [
            UserRole::Founder,
            UserRole::Scolarite,
        ]);
    }

    private function visiblePaymentsQuery(User $user): Builder
    {
        if ($user->hasRole(UserRole::Parent)) {
            return Payment::query()->whereHas('student', fn ($query) => $query->where('parent_id', $user->id));
        }

        return Payment::query();
    }

    private function ensurePaymentVisible(User $user, Payment $payment): void
    {
        abort_unless(
            $this->visiblePaymentsQuery($user)->whereKey($payment->id)->exists(),
            403,
            'Vous ne pouvez pas consulter ce paiement.'
        );
    }

    private function normalizedPaymentData(User $user, array $data, ?Payment $payment = null): array
    {
        $data['received_by_id'] = $payment?->received_by_id ?? $user->id;

        if ($data['status'] === PaymentStatus::Paid->value) {
            $data['validated_by_id'] = $user->id;
            $data['validated_at'] = now();

            return $data;
        }

        $data['validated_by_id'] = null;
        $data['validated_at'] = null;

        return $data;
    }
}
