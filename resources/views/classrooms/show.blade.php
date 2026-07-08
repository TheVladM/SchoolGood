@extends('layouts.app')

@section('title', $classroom->name.' | SchoolGood')
@section('topbar_title', $classroom->name)

@section('content')
    @php
        $sectionValue = $classroom->section?->value;
        $sectionBadge = $sectionValue === 'anglophone' ? 'badge--violet' : 'badge--teal';
        $avatarColor  = $sectionValue === 'anglophone' ? 'avatar--violet' : 'avatar--teal';
        $languageLabel = match ($sectionValue) {
            'francophone' => __('classrooms.lang_teacher_fr'),
            'anglophone'  => __('classrooms.lang_teacher_en'),
            default       => __('classrooms.lang_teacher_default'),
        };
    @endphp

    @include('partials.page-header', [
        'title' => $classroom->name,
        'description' => $classroom->section?->label().' · '.__('classrooms.level_prefix').' '.$classroom->level,
    ])

    <section class="mt-6 detail-grid" data-reveal>

        {{-- Identité de la classe --}}
        <article class="surface-card p-5 lg:p-6">
            <div class="entity-header">
                <div class="entity-header__icon {{ $avatarColor }}" style="{{ $sectionValue === 'anglophone' ? 'background:#ede9fe;color:#7c3aed' : 'background:#ccfbf1;color:#0f766e' }};border-radius:14px;">
                    {{ mb_strtoupper(mb_substr($classroom->name, 0, 2)) }}
                </div>
                <div class="flex-1">
                    <p class="entity-header__name">{{ $classroom->name }}</p>
                    <p class="entity-header__meta">{{ __('classrooms.level_prefix') }} {{ $classroom->level }}</p>
                </div>
                <span class="badge {{ $sectionBadge }}">{{ $classroom->section?->label() }}</span>
            </div>

            <div class="info-list">
                <div class="info-row">
                    <span class="info-key">{{ __('classrooms.info_level') }}</span>
                    <span class="info-val">{{ $classroom->level }}</span>
                </div>
                <div class="info-row">
                    <span class="info-key">{{ __('classrooms.info_room') }}</span>
                    <span class="info-val">{{ $classroom->room ?: '—' }}</span>
                </div>
                <div class="info-row">
                    <span class="info-key">{{ __('classrooms.info_location') }}</span>
                    <span class="info-val">{{ $classroom->location ?: '—' }}</span>
                </div>
                <div class="info-row">
                    <span class="info-key">{{ __('classrooms.info_teacher') }}</span>
                    <span class="info-val">{{ $classroom->mainTeacher?->name ?: '—' }}</span>
                </div>
                <div class="info-row">
                    <span class="info-key">{{ $languageLabel }}</span>
                    <span class="info-val">{{ $classroom->languageTeacher?->name ?: '—' }}</span>
                </div>
            </div>

            <div class="mt-6 flex flex-wrap gap-3">
                <a href="{{ route('classrooms.index') }}" class="btn-secondary">{{ __('ui.back') }}</a>
                @can('update', $classroom)
                    <a href="{{ route('classrooms.edit', $classroom) }}" class="btn-primary">{{ __('ui.edit') }}</a>
                    @if ($classroom->main_teacher_id)
                        <form method="POST" action="{{ route('classrooms.setup-titular-courses', $classroom) }}">
                            @csrf
                            <button type="submit" class="btn-secondary">{{ __('classrooms.auto_courses') }}</button>
                        </form>
                    @else
                        <p class="self-center text-sm text-amber-700">{{ __('classrooms.no_teacher_hint') }}</p>
                    @endif
                @endcan
            </div>
        </article>

        {{-- Données associées --}}
        <article class="surface-card p-5 lg:p-6 space-y-8">

            {{-- Élèves --}}
            <div>
                <h2 class="section-heading">
                    {{ __('classrooms.students_section') }}
                    <span class="text-slate-400 font-normal text-sm ml-1">({{ $classroom->students->count() }})</span>
                </h2>
                @forelse ($classroom->students as $student)
                    <div class="flex items-center gap-3 rounded-xl border border-slate-100 p-3 mb-2 last:mb-0 hover:bg-slate-50 transition-colors">
                        <span class="avatar avatar--indigo" style="font-size:.7rem;">
                            {{ mb_strtoupper(mb_substr($student->first_name, 0, 1)).mb_strtoupper(mb_substr($student->last_name, 0, 1)) }}
                        </span>
                        <div class="flex-1 min-w-0">
                            <a href="{{ route('students.show', $student) }}" class="font-semibold text-slate-900 hover:text-indigo-600 text-sm leading-tight">
                                {{ $student->full_name }}
                            </a>
                            @if ($student->parent?->name)
                                <p class="text-xs text-slate-500 mt-0.5">{{ $student->parent->name }}</p>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="empty-state py-6">
                        <svg class="empty-state__icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 0 0 2.625.372 9.337 9.337 0 0 0 4.121-.952 4.125 4.125 0 0 0-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 0 1 8.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0 1 11.964-3.07M12 6.375a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0Zm8.25 2.25a2.625 2.625 0 1 1-5.25 0 2.625 2.625 0 0 1 5.25 0Z"/></svg>
                        <p class="empty-state__title">{{ __('classrooms.students_section') }}</p>
                        <p class="empty-state__desc">{{ __('classrooms.no_students') }}</p>
                    </div>
                @endforelse
            </div>

            {{-- Cours --}}
            <div>
                <h2 class="section-heading">{{ __('classrooms.courses_section') }}</h2>
                @forelse ($classroom->courses as $course)
                    <div class="rounded-xl border border-slate-100 p-3 mb-2 last:mb-0">
                        <div class="flex items-center justify-between gap-3">
                            <a href="{{ route('courses.show', $course) }}" class="font-semibold text-slate-900 hover:text-indigo-600 text-sm">
                                {{ $course->title }}
                            </a>
                            <span class="badge badge--info">{{ $course->day?->value }}</span>
                        </div>
                        <p class="mt-0.5 text-xs text-slate-500">{{ $course->teacher?->name ?: '—' }}</p>
                    </div>
                @empty
                    <p class="text-sm text-slate-400 italic">{{ __('classrooms.no_courses') }}</p>
                @endforelse
            </div>

            {{-- Emploi du temps --}}
            <div>
                <h2 class="section-heading">{{ __('classrooms.timetable_section') }}</h2>
                @forelse ($classroom->timetableEntries as $entry)
                    <div class="rounded-xl border border-slate-100 p-3 mb-2 last:mb-0">
                        <div class="flex items-center justify-between gap-3">
                            <p class="font-semibold text-slate-900 text-sm">{{ $entry->subject }}</p>
                            <span class="badge badge--amber">{{ $entry->day?->value }}</span>
                        </div>
                        <p class="mt-0.5 text-xs text-slate-500">{{ substr($entry->start_time, 0, 5) }} – {{ substr($entry->end_time, 0, 5) }}</p>
                        @if ($entry->notes)
                            <p class="mt-1 text-xs text-slate-400">{{ $entry->notes }}</p>
                        @endif
                    </div>
                @empty
                    <p class="text-sm text-slate-400 italic">{{ __('classrooms.no_timetable') }}</p>
                @endforelse
            </div>

        </article>
    </section>
@endsection
