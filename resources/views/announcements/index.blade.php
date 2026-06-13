@extends('layouts.app')

@section('title', 'Messages | schoolGood')
@section('topbar_title', 'Messages')

@section('content')
    @include('partials.page-header', [
        'title' => 'Messages',
        'description' => 'Communication avec les parents (validation requise pour la scolarite).',
        'statLabel' => 'Messages',
        'statValue' => $announcements->total(),
    ])

    <section class="surface-card mt-6 p-5 lg:p-6" data-filter-scope data-reveal>
        <div class="toolbar">
            <div>
                <h2 class="section-title">Registre des messages</h2>
                <p class="section-subtitle">Recherchez un titre, une classe, un auteur ou un statut sans quitter la page.</p>
            </div>

            <div class="flex flex-wrap gap-3">
                <label class="search-shell">
                    <span class="search-shell__label">Recherche locale</span>
                    <input type="search" class="field min-w-[18rem]" placeholder="Titre, auteur, classe ou statut" data-table-search>
                </label>

                @if (auth()->user()->hasRole(\App\Enums\UserRole::Founder) && ($pendingCount ?? 0) > 0)
                    <a href="{{ route('announcements.index', ['filter' => 'pending']) }}" class="btn-secondary self-end">
                        En attente ({{ $pendingCount }})
                    </a>
                @endif

                @can('create', \App\Models\Announcement::class)
                    <a href="{{ route('announcements.create') }}" class="btn-primary self-end">Nouveau message</a>
                @endcan
            </div>
        </div>

        <div class="overflow-x-auto table-shell">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Titre</th>
                        <th>Audience</th>
                        <th>Classe</th>
                        <th>Auteur</th>
                        <th>Statut</th>
                        <th class="text-right">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($announcements as $announcement)
                        <tr data-filterable-row>
                            <td class="font-semibold text-slate-900">{{ $announcement->title }}</td>
                            <td>{{ $announcement->audience?->label() }}</td>
                            <td>{{ $announcement->classroom?->name ?: 'Toutes' }}</td>
                            <td>{{ $announcement->author?->name }}</td>
                            <td><span class="badge">{{ $announcement->status?->label() }}</span></td>
                            <td>
                                <div class="record-actions justify-end">
                                    <a href="{{ route('announcements.show', $announcement) }}" class="btn-secondary">Voir</a>
                                    @if (
                                        auth()->user()->hasRole(\App\Enums\UserRole::Founder) ||
                                        auth()->id() === $announcement->author_id
                                    )
                                        <a href="{{ route('announcements.edit', $announcement) }}" class="btn-secondary">Modifier</a>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="empty-state mt-4" data-filter-empty hidden>
            Aucun message ne correspond a cette recherche.
        </div>

        <div class="mt-6">
            {{ $announcements->links() }}
        </div>
    </section>
@endsection
