@extends('layouts.app')

@section('title', 'Devoirs | SchoolGood')
@section('topbar_title', 'Devoirs')

@section('content')
    <section class="page-hero" data-reveal>
        <div>
            <span class="page-hero__eyebrow">Gestion des devoirs</span>
            <h2 class="page-hero__title">Assignez et suivez les devoirs de vos classes.</h2>
            <p class="page-hero__description">
                Les enseignants peuvent assigner des devoirs à leurs classes. Les parents peuvent voir les devoirs de leurs enfants.
            </p>
        </div>

        <div class="page-hero__aside">
            <div class="hero-stat">
                <p class="hero-stat__label">Devoirs</p>
                <p class="hero-stat__value">{{ $homeworks->total() }}</p>
            </div>
            <div class="hero-stat">
                <p class="hero-stat__label">Module</p>
                <p class="hero-stat__value">Pédagogie</p>
            </div>
        </div>
    </section>

    <section class="surface-card mt-6 p-5 lg:p-6" data-filter-scope data-reveal>
        <div class="toolbar">
            <div>
                <h2 class="section-title">Registre des devoirs</h2>
                <p class="section-subtitle">Recherchez un titre, une matière, une classe ou une date limite.</p>
            </div>

            <div class="flex flex-wrap gap-3">
                <label class="search-shell">
                    <span class="search-shell__label">Recherche locale</span>
                    <input type="search" class="field min-w-[18rem]" placeholder="Titre, matière, classe..." data-table-search>
                </label>

                @if (auth()->user()->hasAnyRole([\App\Enums\UserRole::Founder, \App\Enums\UserRole::Admin, \App\Enums\UserRole::Teacher]))
                    <a href="{{ route('homeworks.create') }}" class="btn-primary self-end">Nouveau devoir</a>
                @endif
            </div>
        </div>

        @if ($homeworks->count() > 0)
            <!-- Desktop view -->
            <div class="overflow-x-auto hidden md:block">
                <table class="w-full border-collapse text-sm">
                    <thead>
                        <tr class="border-b border-gray-200 bg-gray-50">
                            <th class="px-4 py-3 text-left font-semibold">Titre</th>
                            <th class="px-4 py-3 text-left font-semibold">Matière</th>
                            <th class="px-4 py-3 text-left font-semibold">Classe</th>
                            <th class="px-4 py-3 text-left font-semibold">Enseignant</th>
                            <th class="px-4 py-3 text-left font-semibold">Date limite</th>
                            <th class="px-4 py-3 text-left font-semibold">Statut</th>
                            <th class="px-4 py-3 text-center font-semibold">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($homeworks as $homework)
                            <tr class="border-b border-gray-100 hover:bg-gray-50 transition" data-filterable-row>
                                <td class="px-4 py-3">
                                    <a href="{{ route('homeworks.show', $homework) }}" class="text-blue-600 hover:text-blue-800 font-medium">
                                        {{ Str::limit($homework->title, 30) }}
                                    </a>
                                </td>
                                <td class="px-4 py-3 text-gray-600">{{ $homework->subject ?? 'N/A' }}</td>
                                <td class="px-4 py-3">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                        {{ $homework->classroom->name }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-gray-600">{{ $homework->teacher->name }}</td>
                                <td class="px-4 py-3">
                                    @if ($homework->isOverdue())
                                        <span class="text-red-600 font-medium">
                                            {{ $homework->due_date->format('d/m/Y H:i') }} ⚠️
                                        </span>
                                    @else
                                        <span class="text-gray-600">
                                            {{ $homework->due_date->format('d/m/Y H:i') }}
                                        </span>
                                    @endif
                                </td>
                                <td class="px-4 py-3">
                                    @if ($homework->status === 'assigned')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                            Assigné
                                        </span>
                                    @elseif ($homework->status === 'submitted')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                            Soumis
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            Noté
                                        </span>
                                    @endif
                                </td>
                                <td class="px-4 py-3 text-center">
                                    <div class="flex justify-center gap-2">
                                        <a href="{{ route('homeworks.show', $homework) }}" class="text-blue-600 hover:text-blue-800" title="Voir">👁️</a>
                                        @can('update', $homework)
                                            <a href="{{ route('homeworks.edit', $homework) }}" class="text-yellow-600 hover:text-yellow-800" title="Éditer">✏️</a>
                                        @endcan
                                        @can('delete', $homework)
                                            <form action="{{ route('homeworks.destroy', $homework) }}" method="POST" class="inline" onsubmit="return confirm('Êtes-vous sûr?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-800" title="Supprimer">🗑️</button>
                                            </form>
                                        @endcan
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Mobile view -->
            <div class="grid gap-4 md:hidden">
                @foreach ($homeworks as $homework)
                    <article class="mobile-record" data-filterable-row>
                        <div class="flex items-start justify-between gap-3">
                            <div class="flex-1">
                                <h3 class="font-semibold text-gray-900">{{ $homework->title }}</h3>
                                <p class="text-sm text-gray-600">{{ $homework->subject ?? 'N/A' }} • {{ $homework->classroom->name }}</p>
                                <p class="text-xs text-gray-500 mt-2">Enseignant: {{ $homework->teacher->name }}</p>
                            </div>
                        </div>
                        <div class="mt-3 pt-3 border-t border-gray-200 flex items-center justify-between">
                            <div class="text-sm">
                                <span class="text-gray-500">Limite:</span>
                                <span class="font-medium @if ($homework->isOverdue()) text-red-600 @endif">
                                    {{ $homework->due_date->format('d/m H:i') }}
                                </span>
                            </div>
                            <div class="flex gap-2">
                                <a href="{{ route('homeworks.show', $homework) }}" class="btn-sm btn-secondary">Voir</a>
                                @can('update', $homework)
                                    <a href="{{ route('homeworks.edit', $homework) }}" class="btn-sm btn-secondary">Éditer</a>
                                @endcan
                            </div>
                        </div>
                    </article>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="mt-6 flex justify-center">
                {{ $homeworks->links() }}
            </div>
        @else
            <div class="text-center py-12">
                <p class="text-gray-500 text-lg">Aucun devoir pour le moment.</p>
                @if (auth()->user()->hasAnyRole([\App\Enums\UserRole::Founder, \App\Enums\UserRole::Admin, \App\Enums\UserRole::Teacher]))
                    <a href="{{ route('homeworks.create') }}" class="btn-primary mt-4 inline-block">Créer le premier devoir</a>
                @endif
            </div>
        @endif
    </section>
@endsection
