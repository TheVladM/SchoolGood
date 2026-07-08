@extends('layouts.app')

@section('title', $schoolYear->name.' | SchoolGood')
@section('topbar_title', __('nav.school_years'))

@section('content')
    @php
        $statusBadge = match ($schoolYear->status?->value) {
            'planned' => 'badge--info',
            'current' => 'badge--success',
            default   => '',
        };
    @endphp

    @include('partials.page-header', [
        'title' => $schoolYear->name,
        'description' => $schoolYear->status?->label(),
    ])

    <section class="mt-6 detail-grid" data-reveal>

        {{-- Identité de l'année --}}
        <article class="surface-card p-5 lg:p-6">
            <div class="entity-header">
                <div class="entity-header__icon" style="background:#e0f2fe;color:#0369a1;border-radius:14px;">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" style="width:1.4rem;height:1.4rem;" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5"/></svg>
                </div>
                <div class="flex-1">
                    <p class="entity-header__name">{{ $schoolYear->name }}</p>
                    <p class="entity-header__meta">
                        {{ $schoolYear->starts_on?->format('d/m/Y') }} – {{ $schoolYear->ends_on?->format('d/m/Y') }}
                    </p>
                </div>
                <span class="badge {{ $statusBadge }}">{{ $schoolYear->status?->label() }}</span>
            </div>

            <div class="info-list">
                <div class="info-row">
                    <span class="info-key">{{ __('school_years.info_period') }}</span>
                    <span class="info-val font-mono text-xs">
                        {{ $schoolYear->starts_on?->format('d/m/Y') }} – {{ $schoolYear->ends_on?->format('d/m/Y') }}
                    </span>
                </div>
                <div class="info-row">
                    <span class="info-key">{{ __('school_years.info_diploma') }}</span>
                    <span class="info-val">{{ $schoolYear->diploma_awarded_on?->format('d/m/Y') ?: '—' }}</span>
                </div>
                <div class="info-row">
                    <span class="info-key">{{ __('school_years.info_promotion_open') }}</span>
                    <span class="info-val">{{ $schoolYear->promotion_opens_on?->format('d/m/Y') ?: '—' }}</span>
                </div>
                <div class="info-row">
                    <span class="info-key">{{ __('school_years.info_next_year') }}</span>
                    <span class="info-val">{{ $schoolYear->nextSchoolYear?->name ?: '—' }}</span>
                </div>
                <div class="info-row">
                    <span class="info-key">{{ __('school_years.info_promoted_at') }}</span>
                    <span class="info-val">{{ $schoolYear->promoted_at?->format('d/m/Y H:i') ?: '—' }}</span>
                </div>
            </div>

            <div class="mt-6 flex flex-wrap gap-3">
                <a href="{{ route('school-years.index') }}" class="btn-secondary">{{ __('ui.back') }}</a>
                <a href="{{ route('school-years.edit', $schoolYear) }}" class="btn-primary">{{ __('ui.edit') }}</a>

                @if ($schoolYear->canPreparePromotions() && $schoolYear->nextSchoolYear)
                    <form method="POST" action="{{ route('school-years.prepare-promotions', $schoolYear) }}">
                        @csrf
                        <button type="submit" class="btn-secondary" onclick="return confirm(&quot;{{ __('school_years.prepare_confirm') }}&quot;)">
                            {{ __('school_years.prepare_promotions') }}
                        </button>
                    </form>
                @endif
            </div>
        </article>

        {{-- Dossiers élèves --}}
        <article class="surface-card p-5 lg:p-6">
            <h2 class="section-heading">
                {{ __('school_years.student_records') }}
                <span class="text-slate-400 font-normal text-sm ml-1">({{ $studentRecords->count() }})</span>
            </h2>
            @forelse ($studentRecords as $record)
                <div class="flex items-center gap-3 rounded-xl border border-slate-100 p-3 mb-2 last:mb-0">
                    <span class="avatar avatar--indigo" style="font-size:.7rem;">
                        {{ mb_strtoupper(mb_substr($record->student?->first_name ?? '?', 0, 1)).mb_strtoupper(mb_substr($record->student?->last_name ?? '', 0, 1)) }}
                    </span>
                    <div class="flex-1 min-w-0">
                        <p class="font-semibold text-slate-900 text-sm leading-tight">{{ $record->student?->full_name ?: '—' }}</p>
                        <p class="text-xs text-slate-500 mt-0.5">{{ $record->classroom_name_snapshot }} · {{ $record->level_snapshot }}</p>
                        @if ($record->student?->parent?->name)
                            <p class="text-xs text-slate-400">{{ $record->student->parent->name }}</p>
                        @endif
                    </div>
                    <span class="badge {{ $record->status?->value === 'active' ? 'badge--success' : '' }}">
                        {{ $record->status?->label() }}
                    </span>
                </div>
            @empty
                <div class="empty-state py-6">
                    <svg class="empty-state__icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 0 0 2.625.372 9.337 9.337 0 0 0 4.121-.952 4.125 4.125 0 0 0-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 0 1 8.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0 1 11.964-3.07M12 6.375a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0Zm8.25 2.25a2.625 2.625 0 1 1-5.25 0 2.625 2.625 0 0 1 5.25 0Z"/></svg>
                    <p class="empty-state__title">{{ __('school_years.no_records') }}</p>
                    <p class="empty-state__desc">{{ __('school_years.no_records_desc') }}</p>
                </div>
            @endforelse
        </article>
    </section>
@endsection
