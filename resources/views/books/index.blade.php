@extends('layouts.app')

@section('title', 'Bibliotheque | schoolGood')
@section('topbar_title', 'Bibliotheque')

@section('content')
    <section class="page-hero" data-reveal>
        <div>
            <span class="page-hero__eyebrow">Ressources pedagogiques</span>
            <h2 class="page-hero__title">Centraliser les livres physiques de l ecole et leur disponibilite en temps reel.</h2>
            <p class="page-hero__description">
                Le registre de bibliotheque facilite la tracabilite des exemplaires, des stocks et des emprunts encore ouverts.
            </p>
        </div>

        <div class="page-hero__aside">
            <div class="hero-stat">
                <p class="hero-stat__label">Livres</p>
                <p class="hero-stat__value">{{ $books->total() }}</p>
            </div>
            <div class="hero-stat">
                <p class="hero-stat__label">Module</p>
                <p class="hero-stat__value">Bibliotheque</p>
            </div>
        </div>
    </section>

    <section class="surface-card mt-6 p-5 lg:p-6" data-filter-scope data-reveal>
        <div class="toolbar">
            <div>
                <h2 class="section-title">Catalogue des livres</h2>
                <p class="section-subtitle">Reperez rapidement un titre, un auteur, une categorie ou un emplacement de rayon.</p>
            </div>

            <div class="flex flex-wrap gap-3">
                <label class="search-shell">
                    <span class="search-shell__label">Recherche locale</span>
                    <input type="search" class="field min-w-[18rem]" placeholder="Titre, auteur, categorie ou rayon" data-table-search>
                </label>

                @if (auth()->user()->hasAnyRole([\App\Enums\UserRole::Founder, \App\Enums\UserRole::Admin, \App\Enums\UserRole::Scolarite]))
                    <a href="{{ route('books.create') }}" class="btn-primary self-end">Nouveau livre</a>
                @endif
            </div>
        </div>

        <div class="grid gap-4 md:hidden">
            @foreach ($books as $book)
                <article class="mobile-record" data-filterable-row>
                    <div class="flex items-start justify-between gap-3">
                        <div>
                            <p class="mobile-record__title">{{ $book->title }}</p>
                            <p class="mt-1 text-sm text-slate-500">{{ $book->author }}</p>
                        </div>
                        <span class="badge">{{ $book->active_loans_count }}/{{ $book->total_copies }}</span>
                    </div>

                    <div class="mobile-record__meta">
                        <p><span class="font-semibold text-slate-900">Disponibles:</span> {{ $book->availableCopies() }}</p>
                        <p><span class="font-semibold text-slate-900">Rayon:</span> {{ $book->shelf_location ?: '-' }}</p>
                    </div>

                    <div class="record-actions">
                        <a href="{{ route('books.show', $book) }}" class="btn-secondary">Voir</a>
                        @if (auth()->user()->hasAnyRole([\App\Enums\UserRole::Founder, \App\Enums\UserRole::Admin, \App\Enums\UserRole::Scolarite]))
                            <a href="{{ route('books.edit', $book) }}" class="btn-secondary">Modifier</a>
                        @endif
                    </div>
                </article>
            @endforeach
        </div>

        <div class="hidden md:block table-shell">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Livre</th>
                        <th>Auteur</th>
                        <th>Stock</th>
                        <th>Disponibles</th>
                        <th>Rayon</th>
                        <th class="text-right">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($books as $book)
                        <tr data-filterable-row>
                            <td class="font-semibold text-slate-900">{{ $book->title }}</td>
                            <td>{{ $book->author }}</td>
                            <td>{{ $book->total_copies }}</td>
                            <td>{{ $book->availableCopies() }}</td>
                            <td>{{ $book->shelf_location ?: '-' }}</td>
                            <td>
                                <div class="record-actions justify-end">
                                    <a href="{{ route('books.show', $book) }}" class="btn-secondary">Voir</a>
                                    @if (auth()->user()->hasAnyRole([\App\Enums\UserRole::Founder, \App\Enums\UserRole::Admin, \App\Enums\UserRole::Scolarite]))
                                        <a href="{{ route('books.edit', $book) }}" class="btn-secondary">Modifier</a>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="empty-state mt-4" data-filter-empty hidden>
            Aucun livre ne correspond a cette recherche.
        </div>

        <div class="mt-6">
            {{ $books->links() }}
        </div>
    </section>
@endsection
