@extends('layouts.app')

@section('title', 'Utilisateurs | schoolGood')
@section('topbar_title', 'Utilisateurs')

@section('content')
    @include('partials.page-header', [
        'title' => 'Utilisateurs',
        'description' => 'Comptes et roles de l etablissement.',
        'statLabel' => 'Comptes',
        'statValue' => $users->total(),
    ])

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

        <div class="overflow-x-auto table-shell">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Nom</th>
                        <th>Email</th>
                        <th>Telephone</th>
                        <th>Service</th>
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
                            <td>{{ $managedUser->department?->label() ?: '-' }}</td>
                            <td><span class="badge">{{ $managedUser->role?->label() }}</span></td>
                            <td>
                                <div class="record-actions justify-end">
                                    <a href="{{ route('users.show', $managedUser) }}" class="btn-secondary">Voir</a>
                                    @can('update', $managedUser)
                                        <a href="{{ route('users.edit', $managedUser) }}" class="btn-secondary">Modifier</a>
                                    @endcan
                                    @can('delete', $managedUser)
                                        <form method="POST" action="{{ route('users.destroy', $managedUser) }}">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn-danger" onclick="return confirm('Supprimer cet utilisateur ?')">
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
            Aucun utilisateur ne correspond a cette recherche.
        </div>

        <div class="mt-6">
            {{ $users->links() }}
        </div>
    </section>
@endsection
