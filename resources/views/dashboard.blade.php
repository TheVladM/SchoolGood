@extends('layouts.app')

@section('title', 'Dashboard | schoolGood')
@section('topbar_title', 'Dashboard')

@section('content')
    @php
        $actions = [
            ['title' => 'Voir les eleves', 'text' => 'Consulter les fiches et les affectations.', 'url' => route('students.index')],
            ['title' => 'Parcourir les classes', 'text' => 'Verifier les sections, salles et effectifs.', 'url' => route('classrooms.index')],
            ['title' => 'Suivre les cours', 'text' => 'Retrouver le planning et les contenus.', 'url' => route('courses.index')],
            ['title' => 'Voir les emplois du temps', 'text' => 'Retrouver les plages horaires par niveau.', 'url' => route('timetable-entries.index')],
        ];

        if (! auth()->user()->hasRole(\App\Enums\UserRole::Parent)) {
            $actions[] = ['title' => 'Bibliotheque', 'text' => 'Suivre les livres et leur disponibilite.', 'url' => route('books.index')];
            $actions[] = ['title' => 'Emprunts', 'text' => 'Voir les sorties, retours et retards.', 'url' => route('book-loans.index')];
        }

        if (auth()->user()->hasAnyRole([\App\Enums\UserRole::Founder, \App\Enums\UserRole::Admin, \App\Enums\UserRole::Scolarite])) {
            $actions[] = ['title' => 'Annees scolaires', 'text' => 'Piloter l historique et les promotions.', 'url' => route('school-years.index')];
        }

        if (auth()->user()->hasAnyRole([\App\Enums\UserRole::Founder, \App\Enums\UserRole::Scolarite, \App\Enums\UserRole::Parent])) {
            $actions[] = ['title' => 'Verifier les paiements', 'text' => 'Suivre les tranches reglees et en attente.', 'url' => route('payments.index')];
        }

        if (! auth()->user()->hasRole(\App\Enums\UserRole::Teacher)) {
            $actions[] = ['title' => 'Messages aux parents', 'text' => 'Rediger, approuver ou consulter les messages.', 'url' => route('announcements.index')];
        }

        if (auth()->user()->hasAnyRole([\App\Enums\UserRole::Founder, \App\Enums\UserRole::Admin])) {
            $actions[] = ['title' => 'Gerer les utilisateurs', 'text' => 'Ajuster les profils et les roles.', 'url' => route('users.index')];
        }
    @endphp

    <section class="page-hero" data-reveal>
        <div>
            <span class="page-hero__eyebrow">{{ auth()->user()->role?->label() }}</span>
            <h2 class="page-hero__title">{{ $headline }}</h2>
            <p class="page-hero__description">{{ $subheadline }}</p>

            <div class="quick-grid mt-6">
                @foreach (collect($actions)->take(4) as $action)
                    <a href="{{ $action['url'] }}" class="quick-card">
                        <p class="quick-card__title">{{ $action['title'] }}</p>
                        <p class="quick-card__text">{{ $action['text'] }}</p>
                    </a>
                @endforeach
            </div>
        </div>

        <div class="page-hero__aside">
            <div class="hero-stat">
                <p class="hero-stat__label">Aujourd hui</p>
                <p class="hero-stat__value">{{ now()->format('d/m/Y') }}</p>
            </div>
            <div class="hero-stat">
                <p class="hero-stat__label">Session</p>
                <p class="hero-stat__value">{{ auth()->user()->name }}</p>
            </div>
        </div>
    </section>

    <section class="metric-grid mt-6" data-reveal>
        @foreach ($stats['cards'] as $card)
            <article class="metric-card">
                <p class="metric-card__label">{{ $card['label'] }}</p>
                <p class="metric-card__value" data-counter="{{ $card['value'] }}">0</p>
                <div class="metric-card__accent"></div>
            </article>
        @endforeach
    </section>

    <section class="mt-6 grid gap-6 xl:grid-cols-[1.3fr_1fr]">
        <div class="grid gap-6" data-reveal>
            <article class="surface-card p-6">
                <div class="flex items-start justify-between gap-4">
                    <div>
                        <h2 class="section-title">Flux d activite</h2>
                        <p class="section-subtitle">Les informations les plus recentes pour garder le bon tempo.</p>
                    </div>
                    <span class="info-chip">Vue metier</span>
                </div>

                <div class="mt-6 grid gap-4 lg:grid-cols-3">
                    <div class="helper-card">
                        <p class="helper-card__title">Eleves recents</p>
                        <div class="timeline-list mt-4">
                            @forelse ($recentStudents as $student)
                                <article class="timeline-item">
                                    <p class="timeline-item__title">{{ $student->full_name }}</p>
                                    <p class="timeline-item__meta">{{ $student->classroom?->name ?? 'Classe non assignee' }}</p>
                                </article>
                            @empty
                                <p class="text-fade text-sm">Aucun eleve a afficher.</p>
                            @endforelse
                        </div>
                    </div>

                    <div class="helper-card">
                        <p class="helper-card__title">Cours recents</p>
                        <div class="timeline-list mt-4">
                            @forelse ($recentCourses as $course)
                                <article class="timeline-item">
                                    <p class="timeline-item__title">{{ $course->title }}</p>
                                    <p class="timeline-item__meta">
                                        {{ $course->classroom?->name }} / {{ $course->teacher?->name }}
                                    </p>
                                </article>
                            @empty
                                <p class="text-fade text-sm">Aucun cours a afficher.</p>
                            @endforelse
                        </div>
                    </div>

                    <div class="helper-card">
                        <p class="helper-card__title">Paiements recents</p>
                        <div class="timeline-list mt-4">
                            @forelse ($recentPayments as $payment)
                                <article class="timeline-item">
                                    <p class="timeline-item__title">{{ $payment->student?->full_name }}</p>
                                    <p class="timeline-item__meta">
                                        {{ $payment->type?->label() }} / {{ number_format((float) $payment->amount, 0, ',', ' ') }} FCFA
                                    </p>
                                </article>
                            @empty
                                <p class="text-fade text-sm">Aucun paiement a afficher pour ce profil.</p>
                            @endforelse
                        </div>
                    </div>
                </div>
            </article>
        </div>

        <aside class="grid gap-6" data-reveal>
            <article class="surface-card p-6">
                <h2 class="section-title">Acces rapides</h2>
                <p class="section-subtitle">Les raccourcis utiles selon votre role actuel.</p>

                <div class="mt-5 grid gap-3">
                    @foreach ($actions as $action)
                        <a href="{{ $action['url'] }}" class="btn-secondary justify-between">
                            <span>{{ $action['title'] }}</span>
                            <span aria-hidden="true">+</span>
                        </a>
                    @endforeach
                </div>
            </article>

            <article class="surface-card p-6">
                <h2 class="section-title">Cadre de pilotage</h2>
                <p class="section-subtitle">
                    Une interface plus claire, plus mobile et plus orientee action pour l equipe schoolGood.
                </p>

                <div class="mt-5 space-y-4">
                    <div class="helper-card">
                        <p class="helper-card__title">Administration</p>
                        <p class="helper-card__text">Centraliser les processus critiques dans des ecrans courts et lisibles.</p>
                    </div>
                    <div class="helper-card">
                        <p class="helper-card__title">Navigation</p>
                        <p class="helper-card__text">Passer d un module a l autre sans se perdre, meme sur mobile.</p>
                    </div>
                    <div class="helper-card">
                        <p class="helper-card__title">Execution</p>
                        <p class="helper-card__text">Aller plus vite sur les listes, les recherches locales et les formulaires.</p>
                    </div>
                </div>
            </article>
        </aside>
    </section>
@endsection
