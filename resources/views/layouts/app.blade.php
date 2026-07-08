<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="theme-color" content="#1877F2">
        <title>@yield('title', config('app.name', 'SchoolGood'))</title>

        <link rel="icon" type="image/png" href="{{ asset('images/schoolgood-logo.png') }}">
        <link rel="apple-touch-icon" href="{{ asset('images/schoolgood-logo.png') }}">

        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,400;0,500;0,600;0,700;1,400&display=swap" rel="stylesheet">

        @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
            @vite(['resources/css/app.css', 'resources/js/app.js'])
        @endif
        {{-- Anti-flash : applique le thème avant le premier rendu --}}
        <script>if(localStorage.getItem('sg-theme')==='dark')document.documentElement.dataset.theme='dark';</script>
    </head>
    <body>
        @include('partials.splash-screen')

        @auth
            @php
                $navItems = [
                    ['route' => 'dashboard', 'label' => __('nav.dashboard'), 'icon' => 'home'],
                    ['route' => 'students.*', 'url' => route('students.index'), 'label' => __('nav.students'), 'icon' => 'users'],
                    ['route' => 'classrooms.*', 'url' => route('classrooms.index'), 'label' => __('nav.classrooms'), 'icon' => 'building'],
                    ['route' => 'courses.*', 'url' => route('courses.index'), 'label' => __('nav.courses'), 'icon' => 'book'],
                    ['route' => 'timetable-entries.*', 'url' => route('timetable-entries.index'), 'label' => __('nav.timetable'), 'icon' => 'calendar'],
                ];

                if (auth()->user()->hasAnyRole([\App\Enums\UserRole::Founder, \App\Enums\UserRole::Admin, \App\Enums\UserRole::Teacher, \App\Enums\UserRole::Parent])) {
                    $navItems[] = ['route' => 'homeworks.*', 'url' => route('homeworks.index'), 'label' => __('nav.homeworks'), 'icon' => 'clipboard'];
                }

                if (auth()->user()->hasAnyRole([\App\Enums\UserRole::Founder, \App\Enums\UserRole::Admin])) {
                    $navItems[] = ['route' => 'rooms.*', 'url' => route('rooms.index'), 'label' => __('nav.rooms'), 'icon' => 'building'];
                }

                if (auth()->user()->hasAnyRole([\App\Enums\UserRole::Founder, \App\Enums\UserRole::Scolarite])) {
                    $navItems[] = ['route' => 'tuition-fees.*', 'url' => route('tuition-fees.index'), 'label' => __('nav.tuition_fees'), 'icon' => 'payment'];
                }

                if (auth()->user()->hasAnyRole([\App\Enums\UserRole::Founder, \App\Enums\UserRole::Admin, \App\Enums\UserRole::Scolarite])) {
                    $navItems[] = ['route' => 'school-years.*', 'url' => route('school-years.index'), 'label' => __('nav.school_years'), 'icon' => 'academic'];
                    $navItems[] = ['route' => 'books.*', 'url' => route('books.index'), 'label' => __('nav.library'), 'icon' => 'library'];
                    $navItems[] = ['route' => 'book-loans.*', 'url' => route('book-loans.index'), 'label' => __('nav.loans'), 'icon' => 'loan'];
                }

                if (auth()->user()->hasRole(\App\Enums\UserRole::Parent)) {
                    $navItems[] = ['route' => 'book-loans.*', 'url' => route('book-loans.index'), 'label' => __('nav.loans'), 'icon' => 'loan'];
                }

                if (auth()->user()->hasAnyRole([\App\Enums\UserRole::Founder, \App\Enums\UserRole::Scolarite, \App\Enums\UserRole::Parent])) {
                    $navItems[] = ['route' => 'payments.*', 'url' => route('payments.index'), 'label' => __('nav.payments'), 'icon' => 'payment'];
                }

                if (auth()->user()->hasRole(\App\Enums\UserRole::Parent)) {
                    $navItems[] = ['route' => 'payments.mobile.*', 'url' => route('payments.mobile.create'), 'label' => __('nav.pay_online'), 'icon' => 'payment'];
                    $navItems[] = ['route' => 'payments.declare', 'url' => route('payments.declare'), 'label' => __('nav.declare_payment'), 'icon' => 'payment'];
                }

                if (! auth()->user()->hasRole(\App\Enums\UserRole::Teacher)) {
                    $navItems[] = ['route' => 'announcements.*', 'url' => route('announcements.index'), 'label' => __('nav.announcements'), 'icon' => 'chat'];
                }

                if (auth()->user()->hasAnyRole([\App\Enums\UserRole::Founder, \App\Enums\UserRole::Admin])) {
                    $navItems[] = ['route' => 'users.*', 'url' => route('users.index'), 'label' => __('nav.users'), 'icon' => 'user-group'];
                }

                $initials = collect(explode(' ', auth()->user()->name))
                    ->filter()
                    ->take(2)
                    ->map(fn ($p) => mb_strtoupper(mb_substr($p, 0, 1)))
                    ->implode('');

                $defaultTopbarTitle = match (true) {
                    request()->routeIs('dashboard')          => __('nav.dashboard'),
                    request()->routeIs('students.*')         => __('nav.students'),
                    request()->routeIs('profile.*')          => __('nav.profile'),
                    request()->routeIs('classrooms.*')       => __('nav.classrooms'),
                    request()->routeIs('courses.*')          => __('nav.courses'),
                    request()->routeIs('timetable-entries.*')=> __('nav.timetable'),
                    request()->routeIs('homeworks.*')        => __('nav.homeworks'),
                    request()->routeIs('school-years.*')     => __('nav.school_years'),
                    request()->routeIs('books.*')            => __('nav.library'),
                    request()->routeIs('book-loans.*')       => __('nav.loans'),
                    request()->routeIs('payments.*')         => __('nav.payments'),
                    request()->routeIs('announcements.*')    => __('nav.announcements'),
                    request()->routeIs('users.*')            => __('nav.users'),
                    default => 'SchoolGood',
                };

                $nextLocale   = app()->getLocale() === 'fr' ? 'en' : 'fr';
                $nextLangLabel = app()->getLocale() === 'fr' ? 'EN' : 'FR';
                $nextLangTitle = app()->getLocale() === 'fr' ? 'Switch to English' : 'Passer en français';
            @endphp

            <div class="app-shell">
                <button class="app-backdrop" type="button" aria-label="Fermer le menu" data-sidebar-close></button>

                <aside class="app-sidebar" aria-label="Navigation">
                    <div class="app-sidebar__inner">
                        <div class="app-sidebar__glow" aria-hidden="true"></div>

                        <a href="{{ route('dashboard') }}" class="sidebar-brand">
                            <x-app-logo size="sm" tagline="Établissement" class="sidebar-brand__logo" />
                        </a>

                        <a href="{{ route('profile.edit') }}" class="sidebar-profile" title="{{ __('nav.profile') }}">
                            <span class="sidebar-profile__avatar">
                                @if(auth()->user()->photoURL)
                                    <img src="{{ auth()->user()->photoURL }}" alt="{{ auth()->user()->name }}">
                                @else
                                    {{ $initials ?: 'U' }}
                                @endif
                            </span>
                            <div class="sidebar-profile__info">
                                <p class="sidebar-profile__name">{{ auth()->user()->name }}</p>
                                <p class="sidebar-profile__role">{{ auth()->user()->role?->label() }}</p>
                            </div>
                        </a>

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
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="sidebar-logout">
                                    <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4M10 17l5-5-5-5M15 12H3"/></svg>
                                    {{ __('ui.logout') }}
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

                            {{-- Bouton langue --}}
                            <a href="{{ route('lang.switch', $nextLocale) }}"
                               class="lang-toggle"
                               title="{{ $nextLangTitle }}">
                                <svg fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 21a9.004 9.004 0 0 0 8.716-6.747M12 21a9.004 9.004 0 0 1-8.716-6.747M12 21c2.485 0 4.5-4.03 4.5-9S14.485 3 12 3m0 18c-2.485 0-4.5-4.03-4.5-9S9.515 3 12 3m0 0a8.997 8.997 0 0 1 7.843 4.582M12 3a8.997 8.997 0 0 0-7.843 4.582m15.686 0A11.953 11.953 0 0 1 12 10.5c-2.998 0-5.74-1.1-7.843-2.918m15.686 0A8.959 8.959 0 0 1 21 12c0 .778-.099 1.533-.284 2.253M3 12c0 .778.099 1.533.284 2.253"/>
                                </svg>
                                {{ $nextLangLabel }}
                            </a>

                            {{-- Bouton thème --}}
                            <button id="theme-toggle" type="button" class="theme-toggle" aria-label="Passer en mode sombre">
                                <svg data-moon width="16" height="16" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M21.752 15.002A9.72 9.72 0 0 1 18 15.75c-5.385 0-9.75-4.365-9.75-9.75 0-1.33.266-2.597.748-3.752A9.753 9.753 0 0 0 3 11.25C3 16.635 7.365 21 12.75 21a9.753 9.753 0 0 0 9.002-5.998Z"/>
                                </svg>
                                <svg data-sun width="16" height="16" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24" aria-hidden="true" style="display:none">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v2.25m6.364.386-1.591 1.591M21 12h-2.25m-.386 6.364-1.591-1.591M12 18.75V21m-4.773-4.227-1.591 1.591M5.25 12H3m4.227-4.773L5.636 5.636M15.75 12a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0Z"/>
                                </svg>
                            </button>

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
            {{-- Bouton langue accessible même pour les guests (page login) --}}
            @php
                $nextLocale   = app()->getLocale() === 'fr' ? 'en' : 'fr';
                $nextLangLabel = app()->getLocale() === 'fr' ? 'EN' : 'FR';
                $nextLangTitle = app()->getLocale() === 'fr' ? 'Switch to English' : 'Passer en français';
            @endphp
            <main class="guest-main">
                @include('partials.flash')
                @yield('content')
            </main>
        @endauth
        @stack('scripts')
    </body>
</html>
