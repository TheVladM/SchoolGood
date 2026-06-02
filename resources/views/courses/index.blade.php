@extends('layouts.app')

@section('title', 'Cours | schoolGood')
@section('topbar_title', 'Cours')

@section('content')
    @include('partials.page-header', [
        'title' => 'Cours',
        'description' => 'Planning, classes et enseignants.',
        'statLabel' => 'Total',
        'statValue' => $courses->total(),
    ])

    <section class="surface-card mt-6 p-5 lg:p-6" data-filter-scope data-reveal>
        <div class="toolbar">
            <div>
                <h2 class="section-title">Catalogue des cours</h2>
                <p class="section-subtitle">Filtrez rapidement par intitule, classe, enseignant ou jour.</p>
            </div>

            <div class="flex flex-wrap gap-3">
                <label class="search-shell">
                    <span class="search-shell__label">Recherche locale</span>
                    <input type="search" class="field min-w-[18rem]" placeholder="Cours, enseignant ou jour" data-table-search>
                </label>

                @can('create', \App\Models\Course::class)
                    <a href="{{ route('courses.create') }}" class="btn-primary self-end">Nouveau cours</a>
                @endcan
            </div>
        </div>

        <div class="grid gap-4 md:hidden">
            @foreach ($courses as $course)
                <article class="mobile-record" data-filterable-row>
                    <div class="flex items-start justify-between gap-3">
                        <div>
                            <p class="mobile-record__title">{{ $course->title }}</p>
                            <p class="mt-1 text-sm text-slate-500">{{ $course->classroom?->name }}</p>
                        </div>
                        <span class="badge">{{ $course->day?->value }}</span>
                    </div>

                    <div class="mobile-record__meta">
                        <p><span class="font-semibold text-slate-900">Enseignant:</span> {{ $course->teacher?->name }}</p>
                    </div>

                    <div class="record-actions">
                        <a href="{{ route('courses.show', $course) }}" class="btn-secondary">Voir</a>

                        @can('update', $course)
                            <a href="{{ route('courses.edit', $course) }}" class="btn-secondary">Modifier</a>
                        @endcan
                        @can('delete', $course)
                            <form method="POST" action="{{ route('courses.destroy', $course) }}">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-danger" onclick="return confirm('Supprimer ce cours ?')">
                                    Supprimer
                                </button>
                            </form>
                        @endcan
                    </div>
                </article>
            @endforeach
        </div>

        <div class="hidden md:block table-shell">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Cours</th>
                        <th>Classe</th>
                        <th>Enseignant</th>
                        <th>Jour</th>
                        <th class="text-right">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($courses as $course)
                        <tr data-filterable-row>
                            <td class="font-semibold text-slate-900">{{ $course->title }}</td>
                            <td>{{ $course->classroom?->name }}</td>
                            <td>{{ $course->teacher?->name }}</td>
                            <td><span class="badge">{{ $course->day?->value }}</span></td>
                            <td>
                                <div class="record-actions justify-end">
                                    <a href="{{ route('courses.show', $course) }}" class="btn-secondary">Voir</a>

                                    @can('update', $course)
                                        <a href="{{ route('courses.edit', $course) }}" class="btn-secondary">Modifier</a>
                                    @endcan
                                    @can('delete', $course)
                                        <form method="POST" action="{{ route('courses.destroy', $course) }}">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn-danger" onclick="return confirm('Supprimer ce cours ?')">
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
            Aucun cours ne correspond a cette recherche.
        </div>

        <div class="mt-6">
            {{ $courses->links() }}
        </div>
    </section>
@endsection
