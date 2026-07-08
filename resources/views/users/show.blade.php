@extends('layouts.app')

@section('title', $managedUser->name.' | SchoolGood')
@section('topbar_title', __('nav.users'))

@section('content')
    @php
        $roleBadge = match ($managedUser->role?->value) {
            'fondateur'  => 'badge--violet',
            'admin'      => 'badge--info',
            'scolarite'  => 'badge--teal',
            'enseignant' => 'badge--amber',
            default      => '',
        };
        $avatarBg = match ($managedUser->role?->value) {
            'fondateur'  => 'background:#ede9fe;color:#7c3aed',
            'admin'      => 'background:#e0f2fe;color:#0369a1',
            'scolarite'  => 'background:#ccfbf1;color:#0f766e',
            'enseignant' => 'background:#fef3c7;color:#b45309',
            default      => 'background:#e0e7ff;color:#4338ca',
        };
    @endphp

    <x-show-shell
        :title="$managedUser->name"
        :description="$managedUser->role?->label()"
        :back-url="route('users.index')"
    >
        <section class="grid gap-6 lg:grid-cols-[0.9fr_1.1fr]">

            {{-- Profil --}}
            <article class="surface-card p-5 lg:p-6">
                <div class="entity-header">
                    @if ($managedUser->photoURL)
                        <img src="{{ $managedUser->photoURL }}" alt="{{ $managedUser->name }}"
                             class="entity-header__icon" style="border-radius:14px;object-fit:cover;">
                    @else
                        <div class="entity-header__icon" style="{{ $avatarBg }};border-radius:14px;font-weight:700;">
                            {{ mb_strtoupper(mb_substr($managedUser->name, 0, 2)) }}
                        </div>
                    @endif
                    <div class="flex-1">
                        <p class="entity-header__name">{{ $managedUser->name }}</p>
                        <p class="entity-header__meta">{{ $managedUser->job_title ?: $managedUser->role?->label() }}</p>
                    </div>
                    <span class="badge {{ $roleBadge }}">{{ $managedUser->role?->label() }}</span>
                </div>

                <div class="info-list">
                    <div class="info-row">
                        <span class="info-key">{{ __('users.info_email') }}</span>
                        <span class="info-val text-sm">{{ $managedUser->email }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-key">{{ __('users.info_phone') }}</span>
                        <span class="info-val">{{ $managedUser->phone ?: '—' }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-key">{{ __('users.info_department') }}</span>
                        <span class="info-val">{{ $managedUser->department?->label() ?: '—' }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-key">{{ __('users.info_job_title') }}</span>
                        <span class="info-val">{{ $managedUser->job_title ?: '—' }}</span>
                    </div>
                </div>

                @can('update', $managedUser)
                    <div class="mt-6">
                        <a href="{{ route('users.edit', $managedUser) }}" class="btn-primary">{{ __('ui.edit') }}</a>
                    </div>
                @endcan
            </article>

            {{-- Indicateurs --}}
            <article class="surface-card p-5 lg:p-6">
                <h2 class="section-heading">{{ __('users.indicators') }}</h2>
                <div class="indicator-grid mt-4">
                    <div class="indicator-card indicator-card--teal">
                        <p class="indicator-card__label">{{ __('users.children_label') }}</p>
                        <p class="indicator-card__value">{{ $managedUser->children_count }}</p>
                    </div>
                    <div class="indicator-card indicator-card--slate">
                        <p class="indicator-card__label">{{ __('users.courses_label') }}</p>
                        <p class="indicator-card__value">{{ $managedUser->courses_count }}</p>
                    </div>
                    <div class="indicator-card indicator-card--amber">
                        <p class="indicator-card__label">{{ __('users.classrooms_label') }}</p>
                        <p class="indicator-card__value">{{ $managedUser->main_classrooms_count }}</p>
                    </div>
                </div>
            </article>
        </section>
    </x-show-shell>
@endsection
