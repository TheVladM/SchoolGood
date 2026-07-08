@extends('layouts.app')

@section('title', __('dashboard.page_title') . ' | SchoolGood')
@section('topbar_title', __('dashboard.topbar_title'))

@section('content')
    @php
        $actions = [
            ['title' => __('dashboard.tile_students'),   'url' => route('students.index'),         'icon' => 'users'],
            ['title' => __('dashboard.tile_classrooms'), 'url' => route('classrooms.index'),       'icon' => 'building'],
            ['title' => __('dashboard.tile_courses'),    'url' => route('courses.index'),           'icon' => 'book'],
            ['title' => __('dashboard.tile_timetable'),  'url' => route('timetable-entries.index'), 'icon' => 'calendar'],
        ];

        if (! auth()->user()->hasRole(\App\Enums\UserRole::Parent)) {
            $actions[] = ['title' => __('dashboard.tile_library'), 'url' => route('books.index'),      'icon' => 'library'];
            $actions[] = ['title' => __('dashboard.tile_loans'),   'url' => route('book-loans.index'), 'icon' => 'loan'];
        }

        if (auth()->user()->hasAnyRole([\App\Enums\UserRole::Founder, \App\Enums\UserRole::Admin, \App\Enums\UserRole::Teacher, \App\Enums\UserRole::Parent])) {
            $actions[] = ['title' => __('dashboard.tile_homeworks'), 'url' => route('homeworks.index'), 'icon' => 'clipboard'];
        }

        if (auth()->user()->hasAnyRole([\App\Enums\UserRole::Founder, \App\Enums\UserRole::Admin, \App\Enums\UserRole::Scolarite])) {
            $actions[] = ['title' => __('dashboard.tile_school_years'), 'url' => route('school-years.index'), 'icon' => 'academic'];
        }

        if (auth()->user()->hasAnyRole([\App\Enums\UserRole::Founder, \App\Enums\UserRole::Scolarite, \App\Enums\UserRole::Parent])) {
            $actions[] = ['title' => __('dashboard.tile_payments'), 'url' => route('payments.index'), 'icon' => 'payment'];
        }

        if (! auth()->user()->hasRole(\App\Enums\UserRole::Teacher)) {
            $actions[] = ['title' => __('dashboard.tile_messages'), 'url' => route('announcements.index'), 'icon' => 'chat'];
        }

        if (auth()->user()->hasAnyRole([\App\Enums\UserRole::Founder, \App\Enums\UserRole::Admin])) {
            $actions[] = ['title' => __('dashboard.tile_users'), 'url' => route('users.index'), 'icon' => 'user-group'];
        }

        $statIcons = ['users', 'building', 'payment', 'clipboard'];
    @endphp

    <section class="dash-hero" data-reveal>
        <div class="dash-hero__copy">
            <p class="dash-hero__greeting">{{ __('dashboard.greeting') }}, {{ strtok(auth()->user()->name, ' ') }}</p>
            <h2 class="dash-hero__title">{{ $headline }}</h2>
            <p class="dash-hero__sub">{{ $subheadline }}</p>
        </div>
        <div class="dash-hero__orb" aria-hidden="true"></div>
    </section>

    @if (! empty($pendingActions))
        <section class="mt-6 grid gap-3 sm:grid-cols-2 lg:grid-cols-3" data-reveal>
            @foreach ($pendingActions as $action)
                <a href="{{ $action['url'] }}" class="surface-card flex items-center justify-between p-4 transition hover:border-indigo-300">
                    <span class="font-semibold text-slate-900">{{ $action['label'] }}</span>
                    @if (($action['count'] ?? null) !== null)
                        <span class="badge">{{ $action['count'] }}</span>
                    @else
                        <x-icon name="payment" class="icon text-indigo-500" />
                    @endif
                </a>
            @endforeach
        </section>
    @endif

    @if (auth()->user()->hasRole(\App\Enums\UserRole::Parent) && $children->isNotEmpty())
        <section class="mt-6 surface-card p-5 lg:p-6" data-reveal>
            <h2 class="section-heading">{{ __('dashboard.my_children') }}</h2>
            <div class="grid gap-4 md:grid-cols-2">
                @foreach ($children as $child)
                    <article class="flex items-start gap-4 rounded-xl border border-slate-200 bg-slate-50 p-4">
                        <div class="avatar avatar--indigo" style="flex-shrink:0;">
                            {{ mb_strtoupper(mb_substr($child->full_name, 0, 2)) }}
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="font-semibold text-slate-900 truncate">{{ $child->full_name }}</p>
                            <p class="text-sm text-slate-500 mb-3">{{ $child->classroom?->name ?? __('dashboard.no_class') }}</p>
                            <div class="flex flex-wrap gap-2">
                                <a href="{{ route('homeworks.index') }}" class="btn-secondary text-xs py-1 px-2">{{ __('dashboard.homework_btn') }}</a>
                                <a href="{{ route('payments.index') }}" class="btn-secondary text-xs py-1 px-2">{{ __('dashboard.payments_btn') }}</a>
                                <a href="{{ route('announcements.index') }}" class="btn-secondary text-xs py-1 px-2">{{ __('dashboard.messages_btn') }}</a>
                            </div>
                        </div>
                    </article>
                @endforeach
            </div>
        </section>
    @endif

    <section class="stat-grid mt-6" data-reveal>
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

    <div class="dash-layout mt-6">
        <section class="content-panel" data-reveal>
            <div class="content-panel__head">
                <div>
                    <h2 class="content-panel__title">{{ __('dashboard.activity_title') }}</h2>
                    <p class="content-panel__subtitle">{{ __('dashboard.activity_subtitle') }}</p>
                </div>
            </div>
            <div class="content-panel__body">
                <div class="activity-grid">
                    <div class="activity-block">
                        <h3 class="activity-block__title">{{ __('dashboard.block_students') }}</h3>
                        <ul class="activity-list">
                            @forelse ($recentStudents as $student)
                                <li>
                                    <span class="activity-list__dot"></span>
                                    <span class="activity-list__main">{{ $student->full_name }}</span>
                                    <span class="activity-list__meta">{{ $student->classroom?->name ?? '-' }}</span>
                                </li>
                            @empty
                                <li class="activity-list__empty">{{ __('dashboard.nothing') }}</li>
                            @endforelse
                        </ul>
                    </div>
                    <div class="activity-block">
                        <h3 class="activity-block__title">{{ __('dashboard.block_courses') }}</h3>
                        <ul class="activity-list">
                            @forelse ($recentCourses as $course)
                                <li>
                                    <span class="activity-list__dot activity-list__dot--violet"></span>
                                    <span class="activity-list__main">{{ $course->title }}</span>
                                    <span class="activity-list__meta">{{ $course->classroom?->name }}</span>
                                </li>
                            @empty
                                <li class="activity-list__empty">{{ __('dashboard.nothing') }}</li>
                            @endforelse
                        </ul>
                    </div>
                    <div class="activity-block">
                        <h3 class="activity-block__title">{{ __('dashboard.block_payments') }}</h3>
                        <ul class="activity-list">
                            @forelse ($recentPayments as $payment)
                                <li>
                                    <span class="activity-list__dot activity-list__dot--amber"></span>
                                    <span class="activity-list__main">{{ $payment->student?->full_name }}</span>
                                    <span class="activity-list__meta">{{ number_format((float) $payment->amount, 0, ',', ' ') }} F</span>
                                </li>
                            @empty
                                <li class="activity-list__empty">{{ __('dashboard.nothing') }}</li>
                            @endforelse
                        </ul>
                    </div>
                    <div class="activity-block">
                        <h3 class="activity-block__title">{{ __('dashboard.block_homeworks') }}</h3>
                        <ul class="activity-list">
                            @forelse ($recentHomeworks as $homework)
                                <li>
                                    <span class="activity-list__dot activity-list__dot--rose"></span>
                                    <span class="activity-list__main">
                                        <a href="{{ route('homeworks.show', $homework) }}" class="hover:text-indigo-600">{{ Str::limit($homework->title, 24) }}</a>
                                    </span>
                                    <span class="activity-list__meta">{{ $homework->classroom?->name }}</span>
                                </li>
                            @empty
                                <li class="activity-list__empty">{{ __('dashboard.nothing') }}</li>
                            @endforelse
                        </ul>
                    </div>
                </div>
            </div>
        </section>

        <aside class="content-panel" data-reveal>
            <div class="content-panel__head">
                <div>
                    <h2 class="content-panel__title">{{ __('dashboard.quick_title') }}</h2>
                    <p class="content-panel__subtitle">{{ __('dashboard.quick_subtitle') }}</p>
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
