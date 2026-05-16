<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>@yield('title', config('app.name', 'schoolGood'))</title>

        @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
            @vite(['resources/css/app.css', 'resources/js/app.js'])
        @endif
    </head>
    <body class="{{ auth()->check() ? 'min-h-screen' : 'min-h-screen px-4 py-4 lg:px-6' }}">
        @auth
            @php
                $navItems = [
                    [
                        'route' => 'dashboard',
                        'label' => 'Dashboard',
                        'eyebrow' => 'Pilotage',
                        'caption' => 'Vue d ensemble',
                    ],
                    [
                        'route' => 'students.*',
                        'url' => route('students.index'),
                        'label' => 'Eleves',
                        'eyebrow' => 'Vie scolaire',
                        'caption' => 'Inscriptions et suivi',
                    ],
                    [
                        'route' => 'classrooms.*',
                        'url' => route('classrooms.index'),
                        'label' => 'Classes',
                        'eyebrow' => 'Organisation',
                        'caption' => 'Sections et salles',
                    ],
                    [
                        'route' => 'courses.*',
                        'url' => route('courses.index'),
                        'label' => 'Cours',
                        'eyebrow' => 'Pedagogie',
                        'caption' => 'Planning et contenu',
                    ],
                    [
                        'route' => 'timetable-entries.*',
                        'url' => route('timetable-entries.index'),
                        'label' => 'Emplois du temps',
                        'eyebrow' => 'Organisation',
                        'caption' => 'Horaires par niveau',
                    ],
                ];

                if (auth()->user()->hasAnyRole([\App\Enums\UserRole::Founder, \App\Enums\UserRole::Scolarite, \App\Enums\UserRole::Parent])) {
                    $navItems[] = [
                        'route' => 'payments.*',
                        'url' => route('payments.index'),
                        'label' => 'Paiements',
                        'eyebrow' => 'Finance',
                        'caption' => 'Tranches et statuts',
                    ];
                }

                if (! auth()->user()->hasRole(\App\Enums\UserRole::Teacher)) {
                    $navItems[] = [
                        'route' => 'announcements.*',
                        'url' => route('announcements.index'),
                        'label' => 'Messages',
                        'eyebrow' => 'Parents',
                        'caption' => 'Validation et diffusion',
                    ];
                }

                if (auth()->user()->hasAnyRole([\App\Enums\UserRole::Founder, \App\Enums\UserRole::Admin])) {
                    $navItems[] = [
                        'route' => 'users.*',
                        'url' => route('users.index'),
                        'label' => 'Utilisateurs',
                        'eyebrow' => 'Acces',
                        'caption' => 'Profils et roles',
                    ];
                }

                $defaultTopbarTitle = match (true) {
                    request()->routeIs('dashboard') => 'Dashboard',
                    request()->routeIs('students.*') => 'Eleves',
                    request()->routeIs('classrooms.*') => 'Classes',
                    request()->routeIs('courses.*') => 'Cours',
                    request()->routeIs('timetable-entries.*') => 'Emplois du temps',
                    request()->routeIs('payments.*') => 'Paiements',
                    request()->routeIs('announcements.*') => 'Messages',
                    request()->routeIs('users.*') => 'Utilisateurs',
                    default => 'Pilotage scolaire',
                };
            @endphp

            <div class="app-shell">
                <button class="app-backdrop" type="button" aria-label="Fermer la navigation" data-sidebar-close></button>

                <aside class="app-sidebar" aria-label="Navigation principale">
                    <div class="app-sidebar__inner">
                        <div class="app-sidebar__scroll">
                            <div class="sidebar-brand">
                                <div class="sidebar-brand__mark">SG</div>
                                <div>
                                    <a href="{{ route('dashboard') }}" class="sidebar-brand__title">schoolGood</a>
                                    <p class="sidebar-brand__text">Campus command center</p>
                                </div>
                            </div>

                            <div class="sidebar-profile">
                                <span class="sidebar-profile__role">{{ auth()->user()->role?->label() }}</span>
                                <p class="sidebar-profile__name">{{ auth()->user()->name }}</p>
                                <p class="sidebar-profile__meta">{{ auth()->user()->email }}</p>
                            </div>

                            <nav class="sidebar-nav">
                                @foreach ($navItems as $item)
                                    @php
                                        $pattern = $item['route'];
                                        $url = $item['url'] ?? route($item['route']);
                                        $isActive = request()->routeIs($pattern);
                                    @endphp

                                    <a href="{{ $url }}" class="nav-link {{ $isActive ? 'is-active' : '' }}">
                                        <span class="nav-link__eyebrow">{{ $item['eyebrow'] }}</span>
                                        <span class="nav-link__label">{{ $item['label'] }}</span>
                                        <span class="nav-link__caption">{{ $item['caption'] }}</span>
                                    </a>
                                @endforeach
                            </nav>

                            <div class="sidebar-footer">
                                <div class="sidebar-note">
                                    <p class="sidebar-note__title">Session active</p>
                                    <p class="sidebar-note__text">
                                        Gardez une vue claire sur les eleves, les classes et les paiements depuis une seule interface.
                                    </p>

                                    <form method="POST" action="{{ route('logout') }}" class="mt-4">
                                        @csrf
                                        <button type="submit" class="btn-danger w-full">Deconnexion</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </aside>

                <div class="app-content">
                    <header class="topbar">
                        <div class="topbar__intro">
                            <button type="button" class="icon-button md:hidden" data-sidebar-toggle aria-label="Ouvrir la navigation">
                                <span class="icon-button__bars"></span>
                            </button>

                            <div>
                                <p class="topbar__eyebrow">schoolGood workspace</p>
                                <h1 class="topbar__title">@yield('topbar_title', $defaultTopbarTitle)</h1>
                            </div>
                        </div>

                        <div class="topbar__meta">
                            <span class="info-chip">{{ now()->format('d/m/Y') }}</span>
                            <span class="info-chip">{{ auth()->user()->role?->label() }}</span>
                        </div>
                    </header>

                    <main class="app-main">
                        @include('partials.flash')
                        @yield('content')
                    </main>
                </div>
            </div>
        @else
            <main class="mx-auto max-w-7xl">
                @include('partials.flash')
                @yield('content')
            </main>
        @endauth
    </body>
</html>
