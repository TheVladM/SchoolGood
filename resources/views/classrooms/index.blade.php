@extends('layouts.app')

@section('title', 'Classes | schoolGood')
@section('topbar_title', 'Classes')

@section('content')
    @include('partials.page-header', [
        'title' => 'Classes',
        'description' => 'Sections, salles, effectifs et enseignants titulaires.',
        'statLabel' => 'Total',
        'statValue' => $classrooms->total(),
    ])

    <section class="surface-card mt-6 p-5 lg:p-6" data-filter-scope data-reveal>
        <div class="toolbar">
            <div>
                <h2 class="section-title">Repertoire des classes</h2>
                <p class="section-subtitle">Retrouvez rapidement une classe par nom, niveau ou enseignant.</p>
            </div>

            <div class="flex flex-wrap gap-3">
                <label class="search-shell">
                    <span class="search-shell__label">Recherche locale</span>
                    <input type="search" class="field min-w-[18rem]" placeholder="Classe, niveau ou enseignant" data-table-search>
                </label>

                @can('create', \App\Models\Classroom::class)
                    <a href="{{ route('classrooms.create') }}" class="btn-primary self-end">Nouvelle classe</a>
                @endcan
            </div>
        </div>

        <div class="grid gap-4 md:hidden">
            @foreach ($classrooms as $classroom)
                <article class="mobile-record" data-filterable-row>
                    <div class="flex items-start justify-between gap-3">
                        <div>
                            <p class="mobile-record__title">{{ $classroom->name }}</p>
                            <p class="mt-1 text-sm text-slate-500">{{ $classroom->level }}</p>
                        </div>
                        <span class="badge">{{ $classroom->section?->label() }}</span>
                    </div>

                    <div class="mobile-record__meta">
                        <p><span class="font-semibold text-slate-900">Titulaire:</span> {{ $classroom->mainTeacher?->name ?: '-' }}</p>
                        <p><span class="font-semibold text-slate-900">Langue:</span> {{ $classroom->languageTeacher?->name ?: '-' }}</p>
                        <p><span class="font-semibold text-slate-900">Effectif:</span> {{ $classroom->students_count }}</p>
                    </div>

                    <div class="record-actions">
                        <a href="{{ route('classrooms.show', $classroom) }}" class="btn-secondary">Voir</a>
                        @can('update', $classroom)
                            <a href="{{ route('classrooms.edit', $classroom) }}" class="btn-secondary">Modifier</a>
                        @endcan
                        @can('delete', $classroom)
                            <form method="POST" action="{{ route('classrooms.destroy', $classroom) }}">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-danger" onclick="return confirm('Supprimer cette classe ?')">
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
                        <th>Classe</th>
                        <th>Niveau</th>
                        <th>Section</th>
                        <th>Titulaire</th>
                        <th>Langue</th>
                        <th>Effectif</th>
                        <th class="text-right">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($classrooms as $classroom)
                        <tr data-filterable-row>
                            <td class="font-semibold text-slate-900">{{ $classroom->name }}</td>
                            <td>{{ $classroom->level }}</td>
                            <td>{{ $classroom->section?->label() }}</td>
                            <td>{{ $classroom->mainTeacher?->name ?: '-' }}</td>
                            <td>{{ $classroom->languageTeacher?->name ?: '-' }}</td>
                            <td>{{ $classroom->students_count }}</td>
                            <td>
                                <div class="record-actions justify-end">
                                    <a href="{{ route('classrooms.show', $classroom) }}" class="btn-secondary">Voir</a>
                                    @can('update', $classroom)
                                        <a href="{{ route('classrooms.edit', $classroom) }}" class="btn-secondary">Modifier</a>
                                    @endcan
                                    @can('delete', $classroom)
                                        <form method="POST" action="{{ route('classrooms.destroy', $classroom) }}">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn-danger" onclick="return confirm('Supprimer cette classe ?')">
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
            Aucune classe ne correspond a cette recherche.
        </div>

        <div class="mt-6">
            {{ $classrooms->links() }}
        </div>
    </section>
@endsection
