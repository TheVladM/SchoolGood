@extends('layouts.app')

@section('title', 'Déclarer un paiement | SchoolGood')
@section('topbar_title', 'Déclarer un paiement')

@section('content')
    @include('partials.page-header', [
        'title' => 'Déclarer un paiement',
        'description' => 'Indiquez votre règlement ; la scolarité le validera après vérification.',
    ])

    <section class="surface-card mt-6 max-w-2xl p-5 lg:p-6 mb-6">
        <h2 class="section-title">Coordonnées de paiement de l\'école</h2>
        <ul class="mt-3 space-y-2 text-sm text-slate-700">
            @foreach ($accounts as $key => $account)
                <li class="rounded-lg border border-slate-200 p-3">
                    <strong>{{ $account['label'] ?? $key }}</strong>
                    @if (! empty($account['number']))<br>Numéro : {{ $account['number'] }}@endif
                    @if (! empty($account['account']))<br>Compte : {{ $account['account'] }} @if(!empty($account['bank_name']))({{ $account['bank_name'] }})@endif @endif
                </li>
            @endforeach
        </ul>
    </section>

    <section class="surface-card max-w-2xl p-5 lg:p-6 mb-6">
        <h2 class="section-title">Paiement en ligne (recommandé)</h2>
        <p class="section-subtitle mt-1">Orange Money ou MTN MoMo avec confirmation automatique par webhook opérateur.</p>
        <a href="{{ route('payments.mobile.create') }}" class="btn-primary mt-4 inline-flex">Payer en ligne</a>
    </section>

    <section class="surface-card max-w-2xl p-5 lg:p-6">
        <h2 class="section-title">Déclaration manuelle</h2>
        <p class="section-subtitle mt-1">Après virement ou paiement hors ligne, indiquez la référence pour validation par la scolarité.</p>
        <form method="POST" action="{{ route('payments.declare.store') }}" class="mt-4 space-y-4">
            @csrf
            <div class="form-field">
                <label class="label" for="student_id">Enfant</label>
                <select id="student_id" name="student_id" class="field" required>
                    @foreach ($children as $child)
                        <option value="{{ $child->id }}">{{ $child->full_name }} — {{ $child->classroom?->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-field">
                <label class="label" for="type">Type</label>
                <select id="type" name="type" class="field" required>
                    @foreach ($types as $value => $label)
                        <option value="{{ $value }}">{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-field">
                <label class="label" for="amount">Montant (FCFA)</label>
                <input id="amount" name="amount" type="number" min="0" step="1" class="field" required>
            </div>
            <div class="form-field">
                <label class="label" for="method">Mode</label>
                <select id="method" name="method" class="field" required>
                    @foreach ($methods as $value => $label)
                        <option value="{{ $value }}">{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-field">
                <label class="label" for="reference">Référence transaction</label>
                <input id="reference" name="reference" class="field">
            </div>
            <div class="form-field">
                <label class="label" for="account_reference">Compte / numéro utilisé</label>
                <input id="account_reference" name="account_reference" class="field">
            </div>
            <button type="submit" class="btn-primary">Envoyer la déclaration</button>
        </form>
    </section>
@endsection
