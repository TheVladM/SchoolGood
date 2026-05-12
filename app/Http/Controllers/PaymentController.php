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
        $this->authorizePaymentAccess($request->user());

        $payments = $this->visiblePaymentsQuery($request->user())
            ->with(['student.classroom'])
            ->latest()
            ->paginate(10);

        return view('payments.index', ['payments' => $payments]);
    }

    public function create(Request $request): View
    {
        $this->authorizeRoles($request->user(), [
            UserRole::Founder,
            UserRole::Admin,
            UserRole::Scolarite,
        ]);

        return view('payments.create', [
            'students' => Student::with('classroom')->orderBy('last_name')->get(),
            'types' => PaymentType::options(),
            'methods' => PaymentMethod::options(),
            'statuses' => PaymentStatus::options(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $this->authorizeRoles($request->user(), [
            UserRole::Founder,
            UserRole::Admin,
            UserRole::Scolarite,
        ]);

        Payment::create($this->validatedData($request));

        return redirect()
            ->route('payments.index')
            ->with('success', 'Paiement enregistre avec succes.');
    }

    public function show(Request $request, Payment $payment): View
    {
        $this->authorizePaymentAccess($request->user());
        $this->ensurePaymentVisible($request->user(), $payment);
        $payment->load(['student.classroom', 'student.parent']);

        return view('payments.show', ['payment' => $payment]);
    }

    public function edit(Request $request, Payment $payment): View
    {
        $this->authorizeRoles($request->user(), [
            UserRole::Founder,
            UserRole::Admin,
            UserRole::Scolarite,
        ]);

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
        $this->authorizeRoles($request->user(), [
            UserRole::Founder,
            UserRole::Admin,
            UserRole::Scolarite,
        ]);

        $payment->update($this->validatedData($request));

        return redirect()
            ->route('payments.index')
            ->with('success', 'Paiement mis a jour avec succes.');
    }

    public function destroy(Request $request, Payment $payment): RedirectResponse
    {
        $this->authorizeRoles($request->user(), [
            UserRole::Founder,
            UserRole::Admin,
            UserRole::Scolarite,
        ]);

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
            'method' => ['required', Rule::enum(PaymentMethod::class)],
            'status' => ['required', Rule::enum(PaymentStatus::class)],
        ]);
    }

    private function authorizePaymentAccess(User $user): void
    {
        $this->authorizeRoles($user, [
            UserRole::Founder,
            UserRole::Admin,
            UserRole::Scolarite,
            UserRole::Parent,
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
}
