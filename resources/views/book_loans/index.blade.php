@extends('layouts.app')

@section('title', 'Emprunts | schoolGood')
@section('topbar_title', 'Emprunts')

@section('content')
    <section class="page-hero" data-reveal>
        <div>
            <span class="page-hero__eyebrow">Mouvements de bibliotheque</span>
            <h2 class="page-hero__title">Suivre chaque sortie de livre, les retours attendus et les retards eventuels.</h2>
            <p class="page-hero__description">
                Chaque emprunt garde son emprunteur, sa date limite et la penalite journaliere applicable.
            </p>
        </div>

        <div class="page-hero__aside">
            <div class="hero-stat">
                <p class="hero-stat__label">Emprunts</p>
                <p class="hero-stat__value">{{ $loans->total() }}</p>
            </div>
            <div class="hero-stat">
                <p class="hero-stat__label">Vue</p>
                <p class="hero-stat__value">Tracabilite</p>
            </div>
        </div>
    </section>

    <section class="surface-card mt-6 p-5 lg:p-6" data-filter-scope data-reveal>
        <div class="toolbar">
            <div>
                <h2 class="section-title">Registre des emprunts</h2>
                <p class="section-subtitle">Recherchez par livre, emprunteur, date limite ou statut de retour.</p>
            </div>

            <div class="flex flex-wrap gap-3">
                <label class="search-shell">
                    <span class="search-shell__label">Recherche locale</span>
                    <input type="search" class="field min-w-[18rem]" placeholder="Livre, eleve, enseignant ou statut" data-table-search>
                </label>

                @if (auth()->user()->hasAnyRole([\App\Enums\UserRole::Founder, \App\Enums\UserRole::Admin, \App\Enums\UserRole::Scolarite]))
                    <a href="{{ route('book-loans.create') }}" class="btn-primary self-end">Nouvel emprunt</a>
                @endif
            </div>
        </div>

        <div class="grid gap-4 md:hidden">
            @foreach ($loans as $loan)
                <article class="mobile-record" data-filterable-row>
                    <div class="flex items-start justify-between gap-3">
                        <div>
                            <p class="mobile-record__title">{{ $loan->book?->title }}</p>
                            <p class="mt-1 text-sm text-slate-500">{{ $loan->borrower_name }}</p>
                        </div>
                        <span class="badge">{{ $loan->returned_at ? 'Retourne' : 'En cours' }}</span>
                    </div>

                    <div class="mobile-record__meta">
                        <p><span class="font-semibold text-slate-900">Retour prevu:</span> {{ $loan->due_at?->format('d/m/Y') }}</p>
                        <p><span class="font-semibold text-slate-900">Penalite actuelle:</span> {{ number_format($loan->penaltyAmount(), 0, ',', ' ') }} FCFA</p>
                    </div>

                    <div class="record-actions">
                        <a href="{{ route('book-loans.show', $loan) }}" class="btn-secondary">Voir</a>
                    </div>
                </article>
            @endforeach
        </div>

        <div class="hidden md:block table-shell">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Livre</th>
                        <th>Emprunteur</th>
                        <th>Emprunt</th>
                        <th>Retour prevu</th>
                        <th>Statut</th>
                        <th class="text-right">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($loans as $loan)
                        <tr data-filterable-row>
                            <td class="font-semibold text-slate-900">{{ $loan->book?->title }}</td>
                            <td>{{ $loan->borrower_name }}</td>
                            <td>{{ $loan->borrowed_at?->format('d/m/Y') }}</td>
                            <td>{{ $loan->due_at?->format('d/m/Y') }}</td>
                            <td><span class="badge">{{ $loan->returned_at ? 'Retourne' : 'En cours' }}</span></td>
                            <td>
                                <div class="record-actions justify-end">
                                    <a href="{{ route('book-loans.show', $loan) }}" class="btn-secondary">Voir</a>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="empty-state mt-4" data-filter-empty hidden>
            Aucun emprunt ne correspond a cette recherche.
        </div>

        <div class="mt-6">
            {{ $loans->links() }}
        </div>
    </section>
@endsection
