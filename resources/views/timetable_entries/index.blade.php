@extends('layouts.app')

@section('title', 'Emplois du temps | schoolGood')
@section('topbar_title', 'Emplois du temps')

@section('content')
    <section class="page-hero" data-reveal>
        <div>
            <span class="page-hero__eyebrow">Organisation des cours</span>
            <h2 class="page-hero__title">Partager un meme emploi du temps a toutes les classes d un meme niveau.</h2>
            <p class="page-hero__description">
                Chaque plage horaire est definie par niveau et section pour eviter de ressaisir les memes horaires classe par classe.
            </p>
        </div>

        <div class="page-hero__aside">
            <div class="hero-stat">
                <p class="hero-stat__label">Creneaux</p>
                <p class="hero-stat__value">{{ $entries->total() }}</p>
            </div>
            <div class="hero-stat">
                <p class="hero-stat__label">Vue</p>
                <p class="hero-stat__value">Horaires</p>
            </div>
        </div>
    </section>

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

                @if (auth()->user()->hasAnyRole([\App\Enums\UserRole::Founder, \App\Enums\UserRole::Admin, \App\Enums\UserRole::Scolarite]))
                    <a href="{{ route('timetable-entries.create') }}" class="btn-primary self-end">Nouveau creneau</a>
                @endif
            </div>
        </div>

        <div class="grid gap-4 md:hidden">
            @foreach ($entries as $entry)
                <article class="mobile-record" data-filterable-row>
                    <div class="flex items-start justify-between gap-3">
                        <div>
                            <p class="mobile-record__title">{{ $entry->subject }}</p>
                            <p class="mt-1 text-sm text-slate-500">{{ $entry->level }} / {{ $entry->section?->label() }}</p>
                        </div>
                        <span class="badge">{{ $entry->day?->value }}</span>
                    </div>

                    <div class="mobile-record__meta">
                        <p><span class="font-semibold text-slate-900">Horaire:</span> {{ substr($entry->start_time, 0, 5) }} - {{ substr($entry->end_time, 0, 5) }}</p>
                    </div>

                    <div class="record-actions">
                        <a href="{{ route('timetable-entries.show', $entry) }}" class="btn-secondary">Voir</a>
                        @if (auth()->user()->hasAnyRole([\App\Enums\UserRole::Founder, \App\Enums\UserRole::Admin, \App\Enums\UserRole::Scolarite]))
                            <a href="{{ route('timetable-entries.edit', $entry) }}" class="btn-secondary">Modifier</a>
                        @endif
                    </div>
                </article>
            @endforeach
        </div>

        <div class="hidden md:block table-shell">
            <table class="data-table">
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
                    @foreach ($entries as $entry)
                        <tr data-filterable-row>
                            <td class="font-semibold text-slate-900">{{ $entry->level }}</td>
                            <td>{{ $entry->section?->label() }}</td>
                            <td>{{ $entry->subject }}</td>
                            <td>{{ $entry->day?->value }}</td>
                            <td>{{ substr($entry->start_time, 0, 5) }} - {{ substr($entry->end_time, 0, 5) }}</td>
                            <td>
                                <div class="record-actions justify-end">
                                    <a href="{{ route('timetable-entries.show', $entry) }}" class="btn-secondary">Voir</a>
                                    @if (auth()->user()->hasAnyRole([\App\Enums\UserRole::Founder, \App\Enums\UserRole::Admin, \App\Enums\UserRole::Scolarite]))
                                        <a href="{{ route('timetable-entries.edit', $entry) }}" class="btn-secondary">Modifier</a>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforeach
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
