@extends('layouts.app')

@section('title', 'Fiche eleve | schoolGood')
@section('topbar_title', 'Fiche eleve')

@section('content')
    <section class="grid gap-6 lg:grid-cols-[0.9fr_1.1fr]" data-reveal>
        <article class="panel p-6">
            <span class="badge">{{ $student->classroom?->name }}</span>
            <h2 class="mt-4 text-3xl font-black text-slate-900">{{ $student->full_name }}</h2>

            <div class="mt-6 space-y-3 text-sm text-slate-600">
                <p><span class="font-semibold text-slate-900">Date de naissance:</span> {{ $student->birth_date?->format('d/m/Y') }}</p>
                <p><span class="font-semibold text-slate-900">Parent:</span> {{ $student->parent?->name }}</p>
                <p><span class="font-semibold text-slate-900">Classe:</span> {{ $student->classroom?->name }}</p>
            </div>
        </article>

        <article class="panel p-6">
            <h2 class="text-xl font-bold text-slate-900">Paiements associes</h2>

            <div class="mt-5 space-y-4">
                @forelse ($student->payments as $payment)
                    <div class="rounded-2xl border border-slate-100 p-4">
                        <div class="flex items-center justify-between gap-3">
                            <p class="font-semibold text-slate-900">{{ $payment->type?->label() }}</p>
                            <span class="badge">{{ $payment->status?->label() }}</span>
                        </div>
                        <p class="mt-1 text-sm text-slate-500">
                            {{ number_format((float) $payment->amount, 0, ',', ' ') }} FCFA / {{ $payment->method?->label() }}
                        </p>
                    </div>
                @empty
                    <p class="text-sm text-slate-500">Aucun paiement associe a cet eleve.</p>
                @endforelse
            </div>

            <div class="mt-6 flex gap-3">
                <a href="{{ route('students.index') }}" class="btn-secondary">Retour</a>
                @if (auth()->user()->hasAnyRole([\App\Enums\UserRole::Founder, \App\Enums\UserRole::Admin, \App\Enums\UserRole::Scolarite]))
                    <a href="{{ route('students.edit', $student) }}" class="btn-primary">Modifier</a>
                @endif
            </div>
        </article>
    </section>
@endsection
