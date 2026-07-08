@extends('layouts.app')

@section('title', $entry->subject.' | SchoolGood')
@section('topbar_title', __('nav.timetable'))

@section('content')
    <x-show-shell
        :title="$entry->subject"
        :description="$entry->level.' · '.$entry->section?->label()"
        :back-url="route('timetable-entries.index')"
    >
        <section class="grid gap-6 lg:grid-cols-[0.9fr_1.1fr]">

            {{-- Détails du créneau --}}
            <article class="surface-card p-5 lg:p-6">
                <div class="entity-header">
                    <div class="entity-header__icon" style="background:#fef9c3;color:#a16207;border-radius:14px;">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" style="width:1.4rem;height:1.4rem;" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5"/></svg>
                    </div>
                    <div class="flex-1">
                        <p class="entity-header__name">{{ $entry->subject }}</p>
                        <p class="entity-header__meta">{{ $entry->level }} · {{ $entry->section?->label() }}</p>
                    </div>
                    @if ($entry->day)
                        <span class="badge badge--amber">{{ $entry->day->value }}</span>
                    @endif
                </div>

                <div class="info-list">
                    <div class="info-row">
                        <span class="info-key">{{ __('timetable.info_level') }}</span>
                        <span class="info-val">{{ $entry->level }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-key">{{ __('timetable.info_section') }}</span>
                        <span class="info-val">
                            <span class="badge {{ $entry->section?->value === 'anglophone' ? 'badge--violet' : 'badge--teal' }}">
                                {{ $entry->section?->label() }}
                            </span>
                        </span>
                    </div>
                    <div class="info-row">
                        <span class="info-key">{{ __('timetable.info_day') }}</span>
                        <span class="info-val">{{ $entry->day?->value ?: '—' }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-key">{{ __('timetable.info_schedule') }}</span>
                        <span class="info-val font-mono">{{ substr($entry->start_time, 0, 5) }} – {{ substr($entry->end_time, 0, 5) }}</span>
                    </div>
                </div>

                @if (auth()->user()->hasAnyRole([\App\Enums\UserRole::Founder, \App\Enums\UserRole::Admin, \App\Enums\UserRole::Scolarite]))
                    <div class="mt-6">
                        <a href="{{ route('timetable-entries.edit', $entry) }}" class="btn-primary">{{ __('ui.edit') }}</a>
                    </div>
                @endif
            </article>

            {{-- Notes --}}
            <article class="surface-card p-5 lg:p-6">
                <h2 class="section-heading">{{ __('timetable.notes') }}</h2>
                @if ($entry->notes)
                    <p class="mt-4 text-sm leading-7 text-slate-600 whitespace-pre-line">{{ $entry->notes }}</p>
                @else
                    <div class="empty-state py-8">
                        <svg class="empty-state__icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z"/></svg>
                        <p class="empty-state__title">{{ __('timetable.no_notes') }}</p>
                        <p class="empty-state__desc">{{ __('timetable.no_notes_desc') }}</p>
                    </div>
                @endif
            </article>
        </section>
    </x-show-shell>
@endsection
