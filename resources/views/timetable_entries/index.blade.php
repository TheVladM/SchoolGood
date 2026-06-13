@extends('layouts.app')

@section('title', 'Emplois du temps | schoolGood')
@section('topbar_title', 'Emplois du temps')

@section('content')
    @include('partials.page-header', [
        'title' => 'Emploi du temps',
        'description' => 'Creneaux par niveau et section.',
        'statLabel' => 'Creneaux',
        'statValue' => $entries->total(),
    ])

    <section class="surface-card mt-6 p-5 lg:p-6" data-filter-scope data-reveal>
        <div class="toolbar">
            <div>
                <h2 class="section-title">Grille horaire</h2>
                <p class="section-subtitle">Recherchez par niveau, jour, matiere ou section directement dans la page.</p>
            </div>

            <div class="flex flex-wrap gap-3">
                <label class="search-shell">
                    <span class="search-shell__label">Recherche locale</span>
                    <input type="search" class="field min-w-[18rem]" placeholder="Niveau, jour, section ou matiere" data-table-search>
                </label>

                @can('create', \App\Models\TimetableEntry::class)
                    <a href="{{ route('timetable-entries.create') }}" class="btn-primary self-end">Nouveau creneau</a>
                @endcan
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="data-table w-full">
                <thead>
                    <tr>
                        <th>Niveau</th>
                        <th>Section</th>
                        <th>Matiere</th>
                        <th>Jour</th>
                        <th>Horaire</th>
                        <th class="text-right">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($entries as $entry)
                        <tr data-filterable-row>
                            <td class="font-semibold text-slate-900">{{ $entry->level }}</td>
                            <td>{{ $entry->section?->label() }}</td>
                            <td>{{ $entry->subject }}</td>
                            <td>{{ $entry->day?->value }}</td>
                            <td>{{ substr($entry->start_time, 0, 5) }} - {{ substr($entry->end_time, 0, 5) }}</td>
                            <td>
                                <div class="record-actions justify-end">
                                    <a href="{{ route('timetable-entries.show', $entry) }}" class="btn-secondary">Voir</a>
                                    @can('update', $entry)
                                        <a href="{{ route('timetable-entries.edit', $entry) }}" class="btn-secondary">Modifier</a>
                                    @endcan
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-4 text-slate-500">Aucun créneau trouvé</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="empty-state mt-4" data-filter-empty hidden>
            Aucun creneau ne correspond a cette recherche.
        </div>

        <div class="mt-6">
            {{ $entries->links() }}
        </div>
    </section>
@endsection
