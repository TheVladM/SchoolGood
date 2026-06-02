@extends('layouts.app')

@section('content')
    @include('partials.page-header', ['title' => $book->title, 'description' => $book->author])

    <section class="mt-6 grid gap-6 xl:grid-cols-[0.9fr_1.1fr]" data-reveal>
        <article class="surface-card p-5 lg:p-6">
            <span class="badge">{{ $book->category ?: 'Livre' }}</span>
            <h1 class="mt-4 text-3xl font-black text-slate-900">{{ $book->title }}</h1>
            <div class="mt-6 space-y-3 text-sm text-slate-600">
                <p><span class="font-semibold text-slate-900">Auteur:</span> {{ $book->author }}</p>
                <p><span class="font-semibold text-slate-900">ISBN:</span> {{ $book->isbn ?: '-' }}</p>
                <p><span class="font-semibold text-slate-900">Langue:</span> {{ $book->language ?: '-' }}</p>
                <p><span class="font-semibold text-slate-900">Stock:</span> {{ $book->total_copies }}</p>
                <p><span class="font-semibold text-slate-900">Disponibles:</span> {{ $book->availableCopies() }}</p>
                <p><span class="font-semibold text-slate-900">Rayon:</span> {{ $book->shelf_location ?: '-' }}</p>
                <p><span class="font-semibold text-slate-900">Penalite / jour:</span> {{ number_format((float) $book->late_fee_per_day, 0, ',', ' ') }} FCFA</p>
                <p><span class="font-semibold text-slate-900">Description:</span> {{ $book->description ?: '-' }}</p>
            </div>

            <div class="mt-6 flex gap-3">
                <a href="{{ route('books.index') }}" class="btn-secondary">Retour</a>
                @if (auth()->user()->hasAnyRole([\App\Enums\UserRole::Founder, \App\Enums\UserRole::Admin, \App\Enums\UserRole::Scolarite]))
                    <a href="{{ route('books.edit', $book) }}" class="btn-primary">Modifier</a>
                @endif
            </div>
        </article>

        <article class="surface-card p-5 lg:p-6">
            <h2 class="text-xl font-bold text-slate-900">Historique des emprunts</h2>
            <div class="mt-5 space-y-4">
                @forelse ($book->loans as $loan)
                    <div class="rounded-2xl border border-slate-100 p-4">
                        <div class="flex items-center justify-between gap-3">
                            <p class="font-semibold text-slate-900">{{ $loan->borrower_name }}</p>
                            <span class="badge">{{ $loan->returned_at ? 'Retourne' : 'En cours' }}</span>
                        </div>
                        <p class="mt-1 text-sm text-slate-500">
                            {{ $loan->borrowed_at?->format('d/m/Y') }} - {{ $loan->due_at?->format('d/m/Y') }}
                        </p>
                        <p class="mt-1 text-sm text-slate-500">
                            Penalite actuelle: {{ number_format($loan->penaltyAmount(), 0, ',', ' ') }} FCFA
                        </p>
                    </div>
                @empty
                    <p class="text-sm text-slate-500">Aucun emprunt enregistre pour ce livre.</p>
                @endforelse
            </div>
        </article>
    </section>
@endsection
