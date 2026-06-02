@extends('layouts.app')

@section('title', 'Paiement en cours | SchoolGood')
@section('topbar_title', 'Paiement en cours')

@section('content')
    @include('partials.page-header', [
        'title' => 'Paiement en attente',
        'description' => 'Référence '.$payment->intent_reference,
    ])

    <section class="surface-card mt-6 max-w-xl p-5 lg:p-6" data-reveal>
        <dl class="space-y-3 text-sm">
            <div class="flex justify-between"><dt class="text-slate-500">Élève</dt><dd class="font-medium">{{ $payment->student?->full_name }}</dd></div>
            <div class="flex justify-between"><dt class="text-slate-500">Montant</dt><dd class="font-medium">{{ number_format((float) $payment->amount, 0, ',', ' ') }} FCFA</dd></div>
            <div class="flex justify-between"><dt class="text-slate-500">Opérateur</dt><dd class="font-medium">{{ $payment->method?->label() }}</dd></div>
            <div class="flex justify-between"><dt class="text-slate-500">Statut</dt><dd class="font-medium">{{ $payment->status?->label() }} / {{ $payment->operator_status ?: '—' }}</dd></div>
        </dl>

        <p class="mt-6 text-sm text-slate-600">
            Dès confirmation par {{ $payment->method?->label() }}, le paiement passera automatiquement à « Payé » et vous recevrez une notification (et un SMS si activé).
        </p>

        <div class="mt-6 flex flex-wrap gap-3">
            <a href="{{ route('payments.index') }}" class="btn-secondary">Mes paiements</a>
            @if ($payment->status === \App\Enums\PaymentStatus::Paid)
                <a href="{{ route('payments.receipt', $payment) }}" class="btn-primary">Télécharger le reçu PDF</a>
            @endif
        </div>
    </section>
@endsection
