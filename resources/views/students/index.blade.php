@extends('layouts.app')

@section('title', 'Eleves | schoolGood')
@section('topbar_title', 'Eleves')

@section('content')
    @include('partials.page-header', [
        'title' => 'Eleves',
        'description' => 'Liste des inscriptions, classes et parents.',
        'statLabel' => 'Total',
        'statValue' => $students->total(),
    ])

    <x-content-panel class="mt-1" data-filter-scope title="Annuaire" subtitle="Filtrez et recherchez sans recharger la page.">
        <x-slot:toolbar>
            <div class="content-panel__toolbar">
                <form method="GET" class="flex flex-wrap items-end gap-3">
                    <label class="search-shell">
                        <span class="search-shell__label">Statut</span>
                        <select name="status_scope" class="field min-w-[12rem]">
                            <option value="active" @selected($statusScope === 'active')>Actifs</option>
                            <option value="archives" @selected($statusScope === 'archives')>Archives</option>
                            <option value="all" @selected($statusScope === 'all')>Tous</option>
                        </select>
                    </label>
                    <label class="search-shell">
                        <span class="search-shell__label">Annee scolaire</span>
                        <select name="school_year_id" class="field min-w-[14rem]">
                            <option value="">Toutes</option>
                            @foreach ($schoolYears as $schoolYear)
                                <option value="{{ $schoolYear->id }}" @selected((string) request('school_year_id') === (string) $schoolYear->id)>
                                    {{ $schoolYear->name }}
                                </option>
                            @endforeach
                        </select>
                    </label>
                    <button type="submit" class="btn-secondary">Filtrer</button>
                </form>

                <label class="search-shell">
                    <span class="search-shell__label">Recherche locale</span>
                    <input type="search" class="field min-w-[18rem]" placeholder="Nom, parent, classe ou statut" data-table-search>
                </label>

                @can('create', \App\Models\Student::class)
                    <a href="{{ route('students.create') }}" class="btn-primary self-end">Nouvel eleve</a>
                @endcan
            </div>
        </x-slot:toolbar>

        <div class="overflow-x-auto table-shell">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Eleve</th>
                        <th>Classe</th>
                        <th>Parent</th>
                        <th>Statut</th>
                        <th>Naissance</th>
                        <th class="text-right">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($students as $student)
                        <tr data-filterable-row>
                            <td class="font-semibold text-slate-900">{{ $student->full_name }}</td>
                            <td>{{ $student->classroom?->name }}</td>
                            <td>{{ $student->parent?->name }}</td>
                            <td><span class="badge">{{ $student->is_active ? 'Actif' : 'Archive' }}</span></td>
                            <td>{{ $student->birth_date?->format('d/m/Y') }}</td>
                            <td>
                                <div class="record-actions justify-end">
                                    <a href="{{ route('students.show', $student) }}" class="btn-secondary">Voir</a>

                                    @can('update', $student)
                                        <a href="{{ route('students.edit', $student) }}" class="btn-secondary">Modifier</a>
                                    @endcan
                                    @can('delete', $student)
                                        <form method="POST" action="{{ route('students.destroy', $student) }}">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn-danger" onclick="return confirm('Supprimer cet eleve ?')">
                                                Supprimer
                                            </button>
                                        </form>
                                    @endcan
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="empty-state mt-4" data-filter-empty hidden>
            Aucun eleve ne correspond a cette recherche.
        </div>

        <div class="mt-6 pagination-wrap">
            {{ $students->links() }}
        </div>
    </x-content-panel>
@endsection
