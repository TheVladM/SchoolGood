@extends('layouts.app')

@section('title', 'Utilisateurs | schoolGood')
@section('topbar_title', 'Utilisateurs')

@section('content')
    <section class="page-hero" data-reveal>
        <div>
            <span class="page-hero__eyebrow">Gestion des acces</span>
            <h2 class="page-hero__title">Piloter les comptes et les roles sans alourdir l experience admin.</h2>
            <p class="page-hero__description">
                Le front met l accent sur la lisibilite des profils, le filtrage rapide et les actions d administration.
            </p>
        </div>

        <div class="page-hero__aside">
            <div class="hero-stat">
                <p class="hero-stat__label">Comptes</p>
                <p class="hero-stat__value">{{ $users->total() }}</p>
            </div>
            <div class="hero-stat">
                <p class="hero-stat__label">Roles disponibles</p>
                <p class="hero-stat__value">{{ count($roles) }}</p>
            </div>
        </div>
    </section>

    <section class="surface-card mt-6 p-5 lg:p-6" data-filter-scope data-reveal>
        <div class="toolbar">
            <div>
                <h2 class="section-title">Gestion des comptes</h2>
                <p class="section-subtitle">Affinez par role puis recherchez un utilisateur directement dans la page.</p>
            </div>

            <div class="flex flex-wrap items-end gap-3">
                <form method="GET" class="flex flex-wrap items-end gap-3">
                    <label class="search-shell">
                        <span class="search-shell__label">Filtre par role</span>
                        <select name="role" class="field min-w-[14rem]">
                            <option value="">Tous les roles</option>
                            @foreach ($roles as $value => $label)
                                <option value="{{ $value }}" @selected(request('role') === $value)>{{ $label }}</option>
                            @endforeach
                        </select>
                    </label>
                    <button type="submit" class="btn-secondary">Filtrer</button>
                </form>

                <label class="search-shell">
                    <span class="search-shell__label">Recherche locale</span>
                    <input type="search" class="field min-w-[16rem]" placeholder="Nom, email ou role" data-table-search>
                </label>

                <a href="{{ route('users.create') }}" class="btn-primary">Nouvel utilisateur</a>
            </div>
        </div>

        <div class="grid gap-4 md:hidden">
            @foreach ($users as $managedUser)
                <article class="mobile-record" data-filterable-row>
                    <div class="flex items-start justify-between gap-3">
                        <div>
                            <p class="mobile-record__title">{{ $managedUser->name }}</p>
                            <p class="mt-1 text-sm text-slate-500">{{ $managedUser->email }}</p>
                        </div>
                        <span class="badge">{{ $managedUser->role?->label() }}</span>
                    </div>

                    <div class="mobile-record__meta">
                        <p><span class="font-semibold text-slate-900">Telephone:</span> {{ $managedUser->phone ?: '-' }}</p>
                    </div>

                    <div class="record-actions">
                        <a href="{{ route('users.show', $managedUser) }}" class="btn-secondary">Voir</a>
                        <a href="{{ route('users.edit', $managedUser) }}" class="btn-secondary">Modifier</a>
                        <form method="POST" action="{{ route('users.destroy', $managedUser) }}">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn-danger" onclick="return confirm('Supprimer cet utilisateur ?')">
                                Supprimer
                            </button>
                        </form>
                    </div>
                </article>
            @endforeach
        </div>

        <div class="hidden md:block table-shell">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Nom</th>
                        <th>Email</th>
                        <th>Telephone</th>
                        <th>Role</th>
                        <th class="text-right">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($users as $managedUser)
                        <tr data-filterable-row>
                            <td class="font-semibold text-slate-900">{{ $managedUser->name }}</td>
                            <td>{{ $managedUser->email }}</td>
                            <td>{{ $managedUser->phone ?: '-' }}</td>
                            <td><span class="badge">{{ $managedUser->role?->label() }}</span></td>
                            <td>
                                <div class="record-actions justify-end">
                                    <a href="{{ route('users.show', $managedUser) }}" class="btn-secondary">Voir</a>
                                    <a href="{{ route('users.edit', $managedUser) }}" class="btn-secondary">Modifier</a>
                                    <form method="POST" action="{{ route('users.destroy', $managedUser) }}">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn-danger" onclick="return confirm('Supprimer cet utilisateur ?')">
                                            Supprimer
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="empty-state mt-4" data-filter-empty hidden>
            Aucun utilisateur ne correspond a cette recherche.
        </div>

        <div class="mt-6">
            {{ $users->links() }}
        </div>
    </section>
@endsection
