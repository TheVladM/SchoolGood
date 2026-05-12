@extends('layouts.app')

@section('content')
    <section class="grid gap-6 lg:grid-cols-[0.9fr_1.1fr]">
        <article class="panel p-6">
            <span class="badge">{{ $payment->status?->label() }}</span>
            <h1 class="mt-4 text-3xl font-black text-slate-900">{{ $payment->student?->full_name }}</h1>
            <div class="mt-6 space-y-3 text-sm text-slate-600">
                <p><span class="font-semibold text-slate-900">Classe:</span> {{ $payment->student?->classroom?->name }}</p>
                <p><span class="font-semibold text-slate-900">Parent:</span> {{ $payment->student?->parent?->name }}</p>
            </div>
        </article>

        <article class="panel p-6">
            <h2 class="text-xl font-bold text-slate-900">Details du paiement</h2>
            <div class="mt-5 space-y-3 text-sm text-slate-600">
                <p><span class="font-semibold text-slate-900">Type:</span> {{ $payment->type?->label() }}</p>
                <p><span class="font-semibold text-slate-900">Montant:</span> {{ number_format((float) $payment->amount, 0, ',', ' ') }} FCFA</p>
                <p><span class="font-semibold text-slate-900">Mode:</span> {{ $payment->method?->label() }}</p>
                <p><span class="font-semibold text-slate-900">Statut:</span> {{ $payment->status?->label() }}</p>
            </div>

            <div class="mt-6 flex gap-3">
                <a href="{{ route('payments.index') }}" class="btn-secondary">Retour</a>
                @if (auth()->user()->hasAnyRole([\App\Enums\UserRole::Founder, \App\Enums\UserRole::Admin, \App\Enums\UserRole::Scolarite]))
                    <a href="{{ route('payments.edit', $payment) }}" class="btn-primary">Modifier</a>
                @endif
            </div>
        </article>
    </section>
@endsection
