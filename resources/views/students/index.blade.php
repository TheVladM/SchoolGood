@extends('layouts.app')

@section('title', 'Eleves | schoolGood')
@section('topbar_title', 'Eleves')

@section('content')
    <section class="page-hero" data-reveal>
        <div>
            <span class="page-hero__eyebrow">Vie scolaire</span>
            <h2 class="page-hero__title">Reperer rapidement les eleves, leurs classes et leurs responsables.</h2>
            <p class="page-hero__description">
                Parcourez les inscriptions avec une lecture plus nette, une recherche locale instantanee
                et des actions plus visibles.
            </p>
        </div>

        <div class="page-hero__aside">
            <div class="hero-stat">
                <p class="hero-stat__label">Elements charges</p>
                <p class="hero-stat__value">{{ $students->total() }}</p>
            </div>
            <div class="hero-stat">
                <p class="hero-stat__label">Vue</p>
                <p class="hero-stat__value">Eleves</p>
            </div>
        </div>
    </section>

    <section class="surface-card mt-6 p-5 lg:p-6" data-filter-scope data-reveal>
        <div class="toolbar">
            <div>
                <h2 class="section-title">Annuaire des eleves</h2>
                <p class="section-subtitle">Recherchez par nom, parent ou classe sans recharger la page.</p>
            </div>

            <div class="flex flex-wrap gap-3">
                <label class="search-shell">
                    <span class="search-shell__label">Recherche locale</span>
                    <input type="search" class="field min-w-[18rem]" placeholder="Nom, parent ou classe" data-table-search>
                </label>

                @if (auth()->user()->hasAnyRole([\App\Enums\UserRole::Founder, \App\Enums\UserRole::Admin, \App\Enums\UserRole::Scolarite]))
                    <a href="{{ route('students.create') }}" class="btn-primary self-end">Nouvel eleve</a>
                @endif
            </div>
        </div>

        <div class="grid gap-4 md:hidden">
            @foreach ($students as $student)
                <article class="mobile-record" data-filterable-row>
                    <div class="flex items-start justify-between gap-3">
                        <div>
                            <p class="mobile-record__title">{{ $student->full_name }}</p>
                            <p class="mt-1 text-sm text-slate-500">{{ $student->classroom?->name }}</p>
                        </div>
                        <span class="badge">{{ $student->birth_date?->format('d/m/Y') }}</span>
                    </div>

                    <div class="mobile-record__meta">
                        <p><span class="font-semibold text-slate-900">Parent:</span> {{ $student->parent?->name }}</p>
                    </div>

                    <div class="record-actions">
                        <a href="{{ route('students.show', $student) }}" class="btn-secondary">Voir</a>

                        @if (auth()->user()->hasAnyRole([\App\Enums\UserRole::Founder, \App\Enums\UserRole::Admin, \App\Enums\UserRole::Scolarite]))
                            <a href="{{ route('students.edit', $student) }}" class="btn-secondary">Modifier</a>
                            <form method="POST" action="{{ route('students.destroy', $student) }}">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-danger" onclick="return confirm('Supprimer cet eleve ?')">
                                    Supprimer
                                </button>
                            </form>
                        @endif
                    </div>
                </article>
            @endforeach
        </div>

        <div class="hidden md:block table-shell">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Eleve</th>
                        <th>Classe</th>
                        <th>Parent</th>
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
                            <td>{{ $student->birth_date?->format('d/m/Y') }}</td>
                            <td>
                                <div class="record-actions justify-end">
                                    <a href="{{ route('students.show', $student) }}" class="btn-secondary">Voir</a>

                                    @if (auth()->user()->hasAnyRole([\App\Enums\UserRole::Founder, \App\Enums\UserRole::Admin, \App\Enums\UserRole::Scolarite]))
                                        <a href="{{ route('students.edit', $student) }}" class="btn-secondary">Modifier</a>
                                        <form method="POST" action="{{ route('students.destroy', $student) }}">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn-danger" onclick="return confirm('Supprimer cet eleve ?')">
                                                Supprimer
                                            </button>
                                        </form>
                                    @endif
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

        <div class="mt-6">
            {{ $students->links() }}
        </div>
    </section>
@endsection
