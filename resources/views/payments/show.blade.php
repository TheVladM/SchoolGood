@extends('layouts.app')

@section('title', 'Paiement | SchoolGood')
@section('topbar_title', 'Détail du paiement')

@section('content')
    @include('partials.page-header', [
        'title' => $payment->student?->full_name ?? 'Paiement',
        'description' => $payment->type?->label() . ' — ' . number_format((float) $payment->amount, 0, ',', ' ') . ' FCFA',
    ])

    <div class="mt-6 grid gap-6 lg:grid-cols-2" data-reveal>
        <section class="surface-card p-5 lg:p-6">
            <span class="badge">{{ $payment->status?->label() }}</span>
            <dl class="mt-4 space-y-3 text-sm">
                <div class="flex justify-between gap-4"><dt class="text-slate-500">Classe</dt><dd class="font-medium">{{ $payment->student?->classroom?->name }}</dd></div>
                <div class="flex justify-between gap-4"><dt class="text-slate-500">Parent</dt><dd class="font-medium">{{ $payment->student?->parent?->name }}</dd></div>
                <div class="flex justify-between gap-4"><dt class="text-slate-500">Mode</dt><dd class="font-medium">{{ $payment->method?->label() }}</dd></div>
                <div class="flex justify-between gap-4"><dt class="text-slate-500">Référence</dt><dd class="font-medium">{{ $payment->intent_reference ?? ($payment->reference ?: '—') }}</dd></div>
                @if ($payment->receipt_number)
                    <div class="flex justify-between gap-4"><dt class="text-slate-500">Reçu</dt><dd class="font-medium">{{ $payment->receipt_number }}</dd></div>
                @endif
                <div class="flex justify-between gap-4"><dt class="text-slate-500">Canal</dt><dd class="font-medium">{{ $payment->channel?->label() ?? '—' }}</dd></div>
                <div class="flex justify-between gap-4"><dt class="text-slate-500">Enregistré par</dt><dd class="font-medium">{{ $payment->receivedBy?->name ?: '—' }}</dd></div>
                <div class="flex justify-between gap-4"><dt class="text-slate-500">Validé par</dt><dd class="font-medium">{{ $payment->validatedBy?->name ?: '—' }}</dd></div>
            </dl>

            <div class="mt-6 flex flex-wrap gap-3">
                <a href="{{ route('payments.index') }}" class="btn-secondary">Retour</a>
                @if ($payment->status === \App\Enums\PaymentStatus::Paid)
                    <a href="{{ route('payments.receipt', $payment) }}" class="btn-primary">Reçu PDF</a>
                @endif
                @can('update', $payment)
                    <a href="{{ route('payments.edit', $payment) }}" class="btn-primary">Modifier</a>
                @endcan
                @can('validate', $payment)
                    @if ($payment->status !== \App\Enums\PaymentStatus::Paid)
                        <form method="POST" action="{{ route('payments.validate', $payment) }}">
                            @csrf
                            <button type="submit" class="btn-primary">Valider le paiement</button>
                        </form>
                    @endif
                @endcan
            </div>
        </section>

        @if ($payment->student && count($installmentBreakdown) > 0)
            <section class="surface-card p-5 lg:p-6">
                <h2 class="section-title">Solde scolarité</h2>
                <p class="mt-2 text-lg font-bold text-rose-700">Reste à payer : {{ number_format($balanceDue, 0, ',', ' ') }} FCFA</p>
                <table class="data-table mt-4">
                    <thead>
                        <tr>
                            <th>Tranche</th>
                            <th>Dû</th>
                            <th>Payé</th>
                            <th>Reste</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($installmentBreakdown as $row)
                            <tr>
                                <td>{{ $row['label'] }}</td>
                                <td>{{ number_format($row['due'], 0, ',', ' ') }}</td>
                                <td>{{ number_format($row['paid'], 0, ',', ' ') }}</td>
                                <td class="font-semibold">{{ number_format($row['remaining'], 0, ',', ' ') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </section>
        @endif
    </div>
@endsection
