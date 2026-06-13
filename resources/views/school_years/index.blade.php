@extends('layouts.app')

@section('title', 'Annees scolaires | schoolGood')
@section('topbar_title', 'Annees scolaires')

@section('content')
    @include('partials.page-header', [
        'title' => 'Annees scolaires',
        'description' => 'Periodes, clotures et promotions.',
        'statLabel' => 'Annees',
        'statValue' => $schoolYears->total(),
    ])

    <section class="surface-card mt-6 p-5 lg:p-6" data-filter-scope data-reveal>
        <div class="toolbar">
            <div>
                <h2 class="section-title">Registre des annees scolaires</h2>
                <p class="section-subtitle">Reperez rapidement la periode courante, la suivante et le volume d historiques deja rattaches.</p>
            </div>

            <div class="flex flex-wrap gap-3">
                <label class="search-shell">
                    <span class="search-shell__label">Recherche locale</span>
                    <input type="search" class="field min-w-[18rem]" placeholder="Nom, statut ou annee suivante" data-table-search>
                </label>

                <a href="{{ route('school-years.create') }}" class="btn-primary self-end">Nouvelle annee</a>
            </div>
        </div>

        <div class="overflow-x-auto table-shell">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Annee</th>
                        <th>Periode</th>
                        <th>Statut</th>
                        <th>Annee suivante</th>
                        <th>Dossiers</th>
                        <th class="text-right">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($schoolYears as $schoolYear)
                        <tr data-filterable-row>
                            <td class="font-semibold text-slate-900">{{ $schoolYear->name }}</td>
                            <td>{{ $schoolYear->starts_on?->format('d/m/Y') }} - {{ $schoolYear->ends_on?->format('d/m/Y') }}</td>
                            <td><span class="badge">{{ $schoolYear->status?->label() }}</span></td>
                            <td>{{ $schoolYear->nextSchoolYear?->name ?: '-' }}</td>
                            <td>{{ $schoolYear->student_records_count }}</td>
                            <td>
                                <div class="record-actions justify-end">
                                    <a href="{{ route('school-years.show', $schoolYear) }}" class="btn-secondary">Voir</a>
                                    <a href="{{ route('school-years.edit', $schoolYear) }}" class="btn-secondary">Modifier</a>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="empty-state mt-4" data-filter-empty hidden>
            Aucune annee scolaire ne correspond a cette recherche.
        </div>

        <div class="mt-6">
            {{ $schoolYears->links() }}
        </div>
    </section>
@endsection
