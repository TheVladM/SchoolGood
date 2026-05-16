@extends('layouts.app')

@section('content')
    <section class="grid gap-6 lg:grid-cols-[0.9fr_1.1fr]">
        <article class="panel p-6">
            <span class="badge">{{ $loan->returned_at ? 'Retourne' : 'En cours' }}</span>
            <h1 class="mt-4 text-3xl font-black text-slate-900">{{ $loan->book?->title }}</h1>
            <div class="mt-6 space-y-3 text-sm text-slate-600">
                <p><span class="font-semibold text-slate-900">Emprunteur:</span> {{ $loan->borrower_name }}</p>
                <p><span class="font-semibold text-slate-900">Date d emprunt:</span> {{ $loan->borrowed_at?->format('d/m/Y') }}</p>
                <p><span class="font-semibold text-slate-900">Retour prevu:</span> {{ $loan->due_at?->format('d/m/Y') }}</p>
                <p><span class="font-semibold text-slate-900">Retour effectif:</span> {{ $loan->returned_at?->format('d/m/Y') ?: '-' }}</p>
                <p><span class="font-semibold text-slate-900">Penalite / jour:</span> {{ number_format((float) $loan->daily_penalty_rate, 0, ',', ' ') }} FCFA</p>
                <p><span class="font-semibold text-slate-900">Penalite actuelle:</span> {{ number_format($loan->penaltyAmount(), 0, ',', ' ') }} FCFA</p>
                <p><span class="font-semibold text-slate-900">Notes:</span> {{ $loan->notes ?: '-' }}</p>
            </div>

            <div class="mt-6 flex flex-wrap gap-3">
                <a href="{{ route('book-loans.index') }}" class="btn-secondary">Retour</a>

                @if (auth()->user()->hasAnyRole([\App\Enums\UserRole::Founder, \App\Enums\UserRole::Admin, \App\Enums\UserRole::Scolarite]))
                    <a href="{{ route('book-loans.edit', $loan) }}" class="btn-primary">Modifier</a>

                    @if (! $loan->returned_at)
                        <form method="POST" action="{{ route('book-loans.return', $loan) }}">
                            @csrf
                            <button type="submit" class="btn-primary">Enregistrer le retour</button>
                        </form>
                    @endif
                @endif
            </div>
        </article>

        <article class="panel p-6">
            <h2 class="text-xl font-bold text-slate-900">Livre et suivi</h2>
            <div class="mt-5 space-y-4">
                <div class="rounded-2xl border border-slate-100 p-4">
                    <p class="font-semibold text-slate-900">{{ $loan->book?->title }}</p>
                    <p class="mt-1 text-sm text-slate-500">{{ $loan->book?->author }}</p>
                    <p class="mt-2 text-sm text-slate-500">Rayon: {{ $loan->book?->shelf_location ?: '-' }}</p>
                </div>

                <div class="rounded-2xl border border-slate-100 p-4">
                    <p class="font-semibold text-slate-900">Traitement</p>
                    <p class="mt-1 text-sm text-slate-500">Sortie enregistree par: {{ $loan->issuedBy?->name ?: '-' }}</p>
                    <p class="mt-1 text-sm text-slate-500">Retour enregistre par: {{ $loan->returnedBy?->name ?: '-' }}</p>
                </div>
            </div>
        </article>
    </section>
@endsection
