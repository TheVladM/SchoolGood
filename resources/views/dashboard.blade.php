@extends('layouts.app')

@section('title', 'Tableau de bord | SchoolGood')
@section('topbar_title', 'Tableau de bord')

@section('content')
    @php
        $actions = [
            ['title' => 'Eleves', 'url' => route('students.index'), 'icon' => 'users'],
            ['title' => 'Classes', 'url' => route('classrooms.index'), 'icon' => 'building'],
            ['title' => 'Cours', 'url' => route('courses.index'), 'icon' => 'book'],
            ['title' => 'Emploi du temps', 'url' => route('timetable-entries.index'), 'icon' => 'calendar'],
        ];

        if (! auth()->user()->hasRole(\App\Enums\UserRole::Parent)) {
            $actions[] = ['title' => 'Bibliotheque', 'url' => route('books.index'), 'icon' => 'library'];
            $actions[] = ['title' => 'Emprunts', 'url' => route('book-loans.index'), 'icon' => 'loan'];
        }

        if (auth()->user()->hasAnyRole([\App\Enums\UserRole::Founder, \App\Enums\UserRole::Admin, \App\Enums\UserRole::Teacher])) {
            $actions[] = ['title' => 'Devoirs', 'url' => route('homeworks.index'), 'icon' => 'clipboard'];
        }

        if (auth()->user()->hasAnyRole([\App\Enums\UserRole::Founder, \App\Enums\UserRole::Admin, \App\Enums\UserRole::Scolarite])) {
            $actions[] = ['title' => 'Annees scolaires', 'url' => route('school-years.index'), 'icon' => 'academic'];
        }

        if (auth()->user()->hasAnyRole([\App\Enums\UserRole::Founder, \App\Enums\UserRole::Scolarite, \App\Enums\UserRole::Parent])) {
            $actions[] = ['title' => 'Paiements', 'url' => route('payments.index'), 'icon' => 'payment'];
        }

        if (! auth()->user()->hasRole(\App\Enums\UserRole::Teacher)) {
            $actions[] = ['title' => 'Messages', 'url' => route('announcements.index'), 'icon' => 'chat'];
        }

        if (auth()->user()->hasAnyRole([\App\Enums\UserRole::Founder, \App\Enums\UserRole::Admin])) {
            $actions[] = ['title' => 'Utilisateurs', 'url' => route('users.index'), 'icon' => 'user-group'];
        }

        $statIcons = ['users', 'building', 'payment', 'clipboard'];
    @endphp

    <section class="dash-hero" data-reveal>
        <div class="dash-hero__copy">
            <p class="dash-hero__greeting">Bonjour, {{ strtok(auth()->user()->name, ' ') }}</p>
            <h2 class="dash-hero__title">{{ $headline }}</h2>
            <p class="dash-hero__sub">{{ $subheadline }}</p>
        </div>
        <div class="dash-hero__orb" aria-hidden="true"></div>
    </section>

    <section class="stat-grid" data-reveal>
        @foreach ($stats['cards'] as $index => $card)
            <article class="stat-card stat-card--{{ ($index % 4) + 1 }}">
                <div class="stat-card__icon">
                    <x-icon :name="$statIcons[$index % count($statIcons)]" class="icon icon--lg" />
                </div>
                <div>
                    <p class="stat-card__label">{{ $card['label'] }}</p>
                    <p class="stat-card__value" data-counter="{{ $card['value'] }}">0</p>
                </div>
            </article>
        @endforeach
    </section>

    <div class="dash-layout">
        <section class="content-panel" data-reveal>
            <div class="content-panel__head">
                <div>
                    <h2 class="content-panel__title">Activite recente</h2>
                    <p class="content-panel__subtitle">Dernieres mises a jour de votre espace</p>
                </div>
            </div>
            <div class="content-panel__body">
                <div class="activity-grid">
                    <div class="activity-block">
                        <h3 class="activity-block__title">Eleves</h3>
                        <ul class="activity-list">
                            @forelse ($recentStudents as $student)
                                <li>
                                    <span class="activity-list__dot"></span>
                                    <span class="activity-list__main">{{ $student->full_name }}</span>
                                    <span class="activity-list__meta">{{ $student->classroom?->name ?? '—' }}</span>
                                </li>
                            @empty
                                <li class="activity-list__empty">Rien a afficher</li>
                            @endforelse
                        </ul>
                    </div>
                    <div class="activity-block">
                        <h3 class="activity-block__title">Cours</h3>
                        <ul class="activity-list">
                            @forelse ($recentCourses as $course)
                                <li>
                                    <span class="activity-list__dot activity-list__dot--violet"></span>
                                    <span class="activity-list__main">{{ $course->title }}</span>
                                    <span class="activity-list__meta">{{ $course->classroom?->name }}</span>
                                </li>
                            @empty
                                <li class="activity-list__empty">Rien a afficher</li>
                            @endforelse
                        </ul>
                    </div>
                    <div class="activity-block">
                        <h3 class="activity-block__title">Paiements</h3>
                        <ul class="activity-list">
                            @forelse ($recentPayments as $payment)
                                <li>
                                    <span class="activity-list__dot activity-list__dot--amber"></span>
                                    <span class="activity-list__main">{{ $payment->student?->full_name }}</span>
                                    <span class="activity-list__meta">{{ number_format((float) $payment->amount, 0, ',', ' ') }} F</span>
                                </li>
                            @empty
                                <li class="activity-list__empty">Rien a afficher</li>
                            @endforelse
                        </ul>
                    </div>
                    @isset($recentHomeworks)
                        <div class="activity-block">
                            <h3 class="activity-block__title">Devoirs</h3>
                            <ul class="activity-list">
                                @forelse ($recentHomeworks as $homework)
                                    <li>
                                        <span class="activity-list__dot activity-list__dot--rose"></span>
                                        <span class="activity-list__main">{{ Str::limit($homework->title, 24) }}</span>
                                        <span class="activity-list__meta">{{ $homework->classroom?->name }}</span>
                                    </li>
                                @empty
                                    <li class="activity-list__empty">Rien a afficher</li>
                                @endforelse
                            </ul>
                        </div>
                    @endisset
                </div>
            </div>
        </section>

        <aside class="content-panel" data-reveal>
            <div class="content-panel__head">
                <div>
                    <h2 class="content-panel__title">Acces rapides</h2>
                    <p class="content-panel__subtitle">Modules selon votre role</p>
                </div>
            </div>
            <div class="content-panel__body">
                <div class="shortcut-grid">
                    @foreach ($actions as $action)
                        <a href="{{ $action['url'] }}" class="shortcut-card">
                            <span class="shortcut-card__icon">
                                <x-icon :name="$action['icon']" class="icon" />
                            </span>
                            <span class="shortcut-card__label">{{ $action['title'] }}</span>
                        </a>
                    @endforeach
                </div>
            </div>
        </aside>
    </div>
@endsection
