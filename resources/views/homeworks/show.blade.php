@extends('layouts.app')

@section('title', $homework->title . ' | SchoolGood')
@section('topbar_title', $homework->title)

@section('content')
    <section class="page-hero" data-reveal>
        <div>
            <span class="page-hero__eyebrow">{{ $homework->subject ?? 'Devoir' }}</span>
            <h2 class="page-hero__title">{{ $homework->title }}</h2>
            <p class="page-hero__description">
                Assigné à la classe <strong>{{ $homework->classroom->name }}</strong> par <strong>{{ $homework->teacher->name }}</strong>
            </p>
        </div>

        <div class="page-hero__aside">
            <div class="hero-stat">
                <p class="hero-stat__label">Date limite</p>
                <p class="hero-stat__value">{{ $homework->due_date->format('d/m/Y') }}</p>
            </div>
            <div class="hero-stat">
                <p class="hero-stat__label">Statut</p>
                <p class="hero-stat__value">
                    @if ($homework->status === 'assigned')
                        Assigné
                    @elseif ($homework->status === 'submitted')
                        Soumis
                    @else
                        Noté
                    @endif
                </p>
            </div>
        </div>
    </section>

    <section class="mt-6 grid lg:grid-cols-3 gap-6" data-reveal>
        <!-- Détails -->
        <div class="lg:col-span-2 space-y-6">
            <div class="surface-card p-5 lg:p-6">
                <h2 class="section-title mb-4">Détails du devoir</h2>
                
                <div class="space-y-4">
                    @if ($homework->description)
                        <div>
                            <p class="text-sm font-semibold text-gray-600 mb-2">Description</p>
                            <div class="p-3 bg-gray-50 rounded border border-gray-200 text-gray-700 whitespace-pre-wrap">
                                {{ $homework->description }}
                            </div>
                        </div>
                    @endif

                    <div class="grid md:grid-cols-2 gap-4">
                        <div>
                            <p class="text-sm font-semibold text-gray-600 mb-1">Matière</p>
                            <p class="text-gray-800">{{ $homework->subject ?? 'Non spécifiée' }}</p>
                        </div>
                        <div>
                            <p class="text-sm font-semibold text-gray-600 mb-1">Classe</p>
                            <p class="text-gray-800">{{ $homework->classroom->name }}</p>
                        </div>
                    </div>

                    <div class="grid md:grid-cols-2 gap-4">
                        <div>
                            <p class="text-sm font-semibold text-gray-600 mb-1">Enseignant</p>
                            <p class="text-gray-800">{{ $homework->teacher->name }}</p>
                        </div>
                        <div>
                            <p class="text-sm font-semibold text-gray-600 mb-1">Email enseignant</p>
                            <p class="text-blue-600">
                                <a href="mailto:{{ $homework->teacher->email }}">{{ $homework->teacher->email }}</a>
                            </p>
                        </div>
                    </div>

                    <div class="grid md:grid-cols-2 gap-4 pt-4 border-t">
                        <div>
                            <p class="text-sm font-semibold text-gray-600 mb-1">Date d'assignation</p>
                            <p class="text-gray-800">{{ $homework->created_at->format('d/m/Y à H:i') }}</p>
                        </div>
                        <div>
                            <p class="text-sm font-semibold text-gray-600 mb-1">Date limite</p>
                            <p class="font-semibold @if ($homework->isOverdue()) text-red-600 @else text-green-600 @endif">
                                {{ $homework->due_date->format('d/m/Y à H:i') }}
                                @if ($homework->isOverdue())
                                    <span class="ml-2">⚠️ En retard</span>
                                @else
                                    <span class="ml-2">✓ {{ $homework->daysUntilDue() }} jours restants</span>
                                @endif
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <div class="flex gap-3">
                @can('update', $homework)
                    <a href="{{ route('homeworks.edit', $homework) }}" class="btn-primary">Éditer</a>
                @endcan
                @can('delete', $homework)
                    <form action="{{ route('homeworks.destroy', $homework) }}" method="POST" class="inline" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce devoir?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn-danger">Supprimer</button>
                    </form>
                @endcan
                <a href="{{ route('homeworks.index') }}" class="btn-secondary">Retour à la liste</a>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="lg:col-span-1">
            <div class="surface-card p-5 rounded-lg sticky top-6">
                <h3 class="font-semibold text-gray-900 mb-4">Informations</h3>
                
                <div class="space-y-3 text-sm">
                    <div class="flex items-center justify-between">
                        <span class="text-gray-600">Statut:</span>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium @if ($homework->status === 'assigned') bg-yellow-100 text-yellow-800 @elseif ($homework->status === 'submitted') bg-blue-100 text-blue-800 @else bg-green-100 text-green-800 @endif">
                            {{ ucfirst($homework->status) }}
                        </span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-gray-600">Section:</span>
                        <span class="font-medium">{{ $homework->classroom->section?->value }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-gray-600">Niveau:</span>
                        <span class="font-medium">{{ $homework->classroom->level }}</span>
                    </div>
                </div>

                <div class="mt-6 pt-4 border-t">
                    <p class="text-xs text-gray-500 mb-2">Créé le</p>
                    <p class="text-sm font-medium text-gray-800">{{ $homework->created_at->format('d/m/Y H:i') }}</p>
                </div>

                @if ($homework->updated_at->notEqualTo($homework->created_at))
                    <div class="mt-3 pt-3 border-t">
                        <p class="text-xs text-gray-500 mb-2">Modifié le</p>
                        <p class="text-sm font-medium text-gray-800">{{ $homework->updated_at->format('d/m/Y H:i') }}</p>
                    </div>
                @endif
            </div>
        </div>
    </section>
@endsection
