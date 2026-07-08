@extends('layouts.app')

@section('title', $course->title.' | SchoolGood')
@section('topbar_title', __('nav.courses'))

@section('content')
    <x-show-shell
        :title="$course->title"
        :description="$course->classroom?->name.' · '.$course->teacher?->name"
        :back-url="route('courses.index')"
    >
        <section class="grid gap-6 lg:grid-cols-[0.9fr_1.1fr]">

            {{-- Détails --}}
            <article class="surface-card p-5 lg:p-6">
                <div class="entity-header">
                    <div class="entity-header__icon" style="background:#e0f2fe;color:#0369a1;border-radius:14px;font-size:1.25rem;">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" style="width:1.4rem;height:1.4rem;" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 0 0 6 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 0 1 6 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 0 1 6-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0 0 18 18a8.967 8.967 0 0 0-6 2.292m0-14.25v14.25"/></svg>
                    </div>
                    <div class="flex-1">
                        <p class="entity-header__name">{{ $course->title }}</p>
                        <p class="entity-header__meta">{{ $course->classroom?->name ?? '—' }}</p>
                    </div>
                    @if ($course->day)
                        <span class="badge badge--info">{{ $course->day->value }}</span>
                    @endif
                </div>

                <div class="info-list">
                    <div class="info-row">
                        <span class="info-key">{{ __('courses.info_classroom') }}</span>
                        <span class="info-val">{{ $course->classroom?->name ?: '—' }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-key">{{ __('courses.info_teacher') }}</span>
                        <span class="info-val">{{ $course->teacher?->name ?: '—' }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-key">{{ __('courses.info_day') }}</span>
                        <span class="info-val">{{ $course->day?->value ?: '—' }}</span>
                    </div>
                    @if ($course->timetable_entry_id)
                        <div class="info-row">
                            <span class="info-key">{{ __('courses.info_source') }}</span>
                            <span class="info-val">{{ __('courses.timetable_sync') }}</span>
                        </div>
                    @endif
                </div>

                @if (
                    auth()->user()->hasAnyRole([\App\Enums\UserRole::Founder, \App\Enums\UserRole::Admin, \App\Enums\UserRole::Scolarite]) ||
                    (auth()->user()->hasRole(\App\Enums\UserRole::Teacher) && $course->teacher_id === auth()->id())
                )
                    <div class="mt-6">
                        <a href="{{ route('courses.edit', $course) }}" class="btn-primary">{{ __('ui.edit') }}</a>
                    </div>
                @endif
            </article>

            {{-- Contenu pédagogique --}}
            <article class="surface-card p-5 lg:p-6">
                <h2 class="section-heading">{{ __('courses.pedagogic') }}</h2>
                @if ($course->content)
                    <p class="mt-4 whitespace-pre-line text-sm text-slate-600 leading-relaxed">{{ $course->content }}</p>
                @else
                    <div class="empty-state py-8">
                        <svg class="empty-state__icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z"/></svg>
                        <p class="empty-state__title">{{ __('courses.no_content') }}</p>
                        <p class="empty-state__desc">{{ __('courses.no_content_desc') }}</p>
                    </div>
                @endif
            </article>
        </section>
    </x-show-shell>
@endsection
