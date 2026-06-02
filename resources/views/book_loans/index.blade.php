@extends('layouts.app')

@section('title', 'Emprunts | schoolGood')
@section('topbar_title', 'Emprunts')

@section('content')
    @include('partials.page-header', [
        'title' => 'Emprunts',
        'description' => 'Sorties, retours et penalites de retard.',
        'statLabel' => 'Emprunts',
        'statValue' => $loans->total(),
    ])

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

                @can('create', \App\Models\BookLoan::class)
                    <a href="{{ route('book-loans.create') }}" class="btn-primary self-end">Nouvel emprunt</a>
                @endcan
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
