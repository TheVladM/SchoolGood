<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="theme-color" content="#0f172a">
        <title>@yield('title', config('app.name', 'SchoolGood'))</title>

        <link rel="icon" type="image/png" href="{{ asset('images/schoolgood-logo.png') }}">
        <link rel="apple-touch-icon" href="{{ asset('images/schoolgood-logo.png') }}">

        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,400;0,500;0,600;0,700;1,400&display=swap" rel="stylesheet">

        @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
            @vite(['resources/css/app.css', 'resources/js/app.js'])
        @endif
    </head>
    <body>
        @include('partials.splash-screen')

        @auth
            @php
                $navItems = [
                    ['route' => 'dashboard', 'label' => 'Tableau de bord', 'icon' => 'home'],
                    ['route' => 'students.*', 'url' => route('students.index'), 'label' => 'Élèves', 'icon' => 'users'],
                    ['route' => 'classrooms.*', 'url' => route('classrooms.index'), 'label' => 'Classes', 'icon' => 'building'],
                    ['route' => 'courses.*', 'url' => route('courses.index'), 'label' => 'Cours', 'icon' => 'book'],
                    ['route' => 'timetable-entries.*', 'url' => route('timetable-entries.index'), 'label' => 'Emploi du temps', 'icon' => 'calendar'],
                ];

                if (auth()->user()->hasAnyRole([\App\Enums\UserRole::Founder, \App\Enums\UserRole::Admin, \App\Enums\UserRole::Teacher, \App\Enums\UserRole::Parent])) {
                    $navItems[] = ['route' => 'homeworks.*', 'url' => route('homeworks.index'), 'label' => 'Devoirs', 'icon' => 'clipboard'];
                }

                if (auth()->user()->hasAnyRole([\App\Enums\UserRole::Founder, \App\Enums\UserRole::Admin])) {
                    $navItems[] = ['route' => 'rooms.*', 'url' => route('rooms.index'), 'label' => 'Salles', 'icon' => 'building'];
                }

                if (auth()->user()->hasAnyRole([\App\Enums\UserRole::Founder, \App\Enums\UserRole::Scolarite])) {
                    $navItems[] = ['route' => 'tuition-fees.*', 'url' => route('tuition-fees.index'), 'label' => 'Frais scolarité', 'icon' => 'payment'];
                }

                if (auth()->user()->hasAnyRole([\App\Enums\UserRole::Founder, \App\Enums\UserRole::Admin, \App\Enums\UserRole::Scolarite])) {
                    $navItems[] = ['route' => 'school-years.*', 'url' => route('school-years.index'), 'label' => 'Années scolaires', 'icon' => 'academic'];
                    $navItems[] = ['route' => 'books.*', 'url' => route('books.index'), 'label' => 'Bibliothèque', 'icon' => 'library'];
                    $navItems[] = ['route' => 'book-loans.*', 'url' => route('book-loans.index'), 'label' => 'Emprunts', 'icon' => 'loan'];
                }

                if (auth()->user()->hasRole(\App\Enums\UserRole::Parent)) {
                    $navItems[] = ['route' => 'book-loans.*', 'url' => route('book-loans.index'), 'label' => 'Emprunts', 'icon' => 'loan'];
                }

                if (auth()->user()->hasAnyRole([\App\Enums\UserRole::Founder, \App\Enums\UserRole::Scolarite, \App\Enums\UserRole::Parent])) {
                    $navItems[] = ['route' => 'payments.*', 'url' => route('payments.index'), 'label' => 'Paiements', 'icon' => 'payment'];
                }

                if (auth()->user()->hasRole(\App\Enums\UserRole::Parent)) {
                    $navItems[] = ['route' => 'payments.mobile.*', 'url' => route('payments.mobile.create'), 'label' => 'Payer en ligne', 'icon' => 'payment'];
                    $navItems[] = ['route' => 'payments.declare', 'url' => route('payments.declare'), 'label' => 'Déclarer paiement', 'icon' => 'payment'];
                }

                if (! auth()->user()->hasRole(\App\Enums\UserRole::Teacher)) {
                    $navItems[] = ['route' => 'announcements.*', 'url' => route('announcements.index'), 'label' => 'Messages', 'icon' => 'chat'];
                }

                if (auth()->user()->hasAnyRole([\App\Enums\UserRole::Founder, \App\Enums\UserRole::Admin])) {
                    $navItems[] = ['route' => 'users.*', 'url' => route('users.index'), 'label' => 'Utilisateurs', 'icon' => 'user-group'];
                }

                $initials = collect(explode(' ', auth()->user()->name))
                    ->filter()
                    ->take(2)
                    ->map(fn ($p) => mb_strtoupper(mb_substr($p, 0, 1)))
                    ->implode('');

                $defaultTopbarTitle = match (true) {
                    request()->routeIs('dashboard') => 'Tableau de bord',
                    request()->routeIs('students.*') => 'Élèves',
                    request()->routeIs('profile.*') => 'Mon profil',
                    request()->routeIs('classrooms.*') => 'Classes',
                    request()->routeIs('courses.*') => 'Cours',
                    request()->routeIs('timetable-entries.*') => 'Emploi du temps',
                    request()->routeIs('homeworks.*') => 'Devoirs',
                    request()->routeIs('school-years.*') => 'Années scolaires',
                    request()->routeIs('books.*') => 'Bibliothèque',
                    request()->routeIs('book-loans.*') => 'Emprunts',
                    request()->routeIs('payments.*') => 'Paiements',
                    request()->routeIs('announcements.*') => 'Messages',
                    request()->routeIs('users.*') => 'Utilisateurs',
                    default => 'SchoolGood',
                };
            @endphp

            <div class="app-shell">
                <button class="app-backdrop" type="button" aria-label="Fermer le menu" data-sidebar-close></button>

                <aside class="app-sidebar" aria-label="Navigation">
                    <div class="app-sidebar__inner">
                        <div class="app-sidebar__glow" aria-hidden="true"></div>

                        <a href="{{ route('dashboard') }}" class="sidebar-brand">
                            <x-app-logo size="sm" tagline="Établissement" variant="on-dark" class="sidebar-brand__logo" />
                        </a>

                        <div class="sidebar-profile">
                            <span class="sidebar-profile__avatar">{{ $initials ?: 'U' }}</span>
                            <div class="sidebar-profile__info">
                                <p class="sidebar-profile__name">{{ auth()->user()->name }}</p>
                                <p class="sidebar-profile__role">{{ auth()->user()->role?->label() }}</p>
                            </div>
                        </div>

                        <nav class="sidebar-nav">
                            @foreach ($navItems as $item)
                                @php
                                    $url = $item['url'] ?? route($item['route']);
                                    $isActive = request()->routeIs($item['route']);
                                @endphp
                                <a href="{{ $url }}" class="nav-link {{ $isActive ? 'is-active' : '' }}">
                                    <x-icon :name="$item['icon']" class="nav-link__icon" />
                                    <span>{{ $item['label'] }}</span>
                                </a>
                            @endforeach
                        </nav>

                        <div class="sidebar-footer">
                            <a href="{{ route('profile.edit') }}" class="nav-link mb-2">
                                <x-icon name="user-group" class="nav-link__icon" />
                                <span>Mon profil</span>
                            </a>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="sidebar-logout">
                                    <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4M10 17l5-5-5-5M15 12H3"/></svg>
                                    Déconnexion
                                </button>
                            </form>
                        </div>
                    </div>
                </aside>

                <div class="app-content">
                    <header class="topbar">
                        <div class="topbar__left">
                            <button type="button" class="icon-button" data-sidebar-toggle aria-label="Menu">
                                <span class="icon-button__bars"></span>
                            </button>
                            <div>
                                <p class="topbar__breadcrumb">SchoolGood</p>
                                <h1 class="topbar__title">@yield('topbar_title', $defaultTopbarTitle)</h1>
                            </div>
                        </div>
                        <div class="topbar__right">
                            <time class="topbar__date" datetime="{{ now()->toDateString() }}">{{ now()->format('d M Y') }}</time>
                            <span class="topbar__pill">{{ auth()->user()->role?->label() }}</span>
                        </div>
                    </header>

                    <main class="app-main">
                        @include('partials.flash')
                        @yield('content')
                    </main>
                </div>
            </div>
        @else
            <main class="guest-main">
                @include('partials.flash')
                @yield('content')
            </main>
        @endauth
        @stack('scripts')
    </body>
</html>
