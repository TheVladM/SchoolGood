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

        <div class="overflow-x-auto table-shell">
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
