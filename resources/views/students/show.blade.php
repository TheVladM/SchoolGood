@extends('layouts.app')

@section('title', $student->full_name.' | SchoolGood')
@section('topbar_title', __('students.show_title'))

@section('content')
    @include('partials.page-header', [
        'title'       => $student->full_name,
        'description' => $student->classroom?->name ?? __('students.no_class'),
    ])

    <section class="mt-6 detail-grid" data-reveal>

        {{-- Identité --}}
        <article class="surface-card detail-card">
            <div class="entity-header">
                <div class="entity-header__icon" style="background:#e0e7ff;color:#4338ca;border-radius:14px;">
                    {{ mb_strtoupper(mb_substr($student->first_name, 0, 1)).mb_strtoupper(mb_substr($student->last_name, 0, 1)) }}
                </div>
                <div class="flex-1">
                    <p class="entity-header__name">{{ $student->full_name }}</p>
                    <p class="entity-header__meta">{{ $student->classroom?->name ?? __('students.no_class') }}</p>
                </div>
                <span class="badge {{ $student->is_active ? 'badge--success' : '' }}">
                    {{ $student->is_active ? __('students.active') : __('students.archived') }}
                </span>
            </div>

            <div class="info-list">
                <div class="info-row">
                    <span class="info-key">{{ __('students.form_birth_date') }}</span>
                    <span class="info-val">{{ $student->birth_date?->format('d/m/Y') ?: '—' }}</span>
                </div>
                <div class="info-row">
                    <span class="info-key">{{ __('students.col_parent') }}</span>
                    <span class="info-val">
                        @if ($student->parent)
                            <a href="{{ route('users.show', $student->parent) }}" class="link">{{ $student->parent->name }}</a>
                        @else
                            —
                        @endif
                    </span>
                </div>
                <div class="info-row">
                    <span class="info-key">{{ __('students.form_classroom') }}</span>
                    <span class="info-val">
                        @if ($student->classroom)
                            <a href="{{ route('classrooms.show', $student->classroom) }}" class="link">{{ $student->classroom->name }}</a>
                        @else
                            —
                        @endif
                    </span>
                </div>
            </div>

            <div class="form-actions" style="margin-top:1.5rem;">
                <a href="{{ route('students.index') }}" class="btn-secondary">{{ __('ui.back') }}</a>
                @if (auth()->user()->hasAnyRole([\App\Enums\UserRole::Founder, \App\Enums\UserRole::Admin, \App\Enums\UserRole::Scolarite]))
                    <a href="{{ route('students.edit', $student) }}" class="btn-primary">{{ __('ui.edit') }}</a>
                @endif
            </div>
        </article>

        {{-- Données associées --}}
        <article class="surface-card detail-card sections-stack">

            {{-- Paiements --}}
            <div>
                <h2 class="section-heading">{{ __('students.payments') }}</h2>
                @forelse ($student->payments as $payment)
                    <a href="{{ route('payments.show', $payment) }}" class="data-row">
                        <div class="data-row__body">
                            <p class="data-row__title">{{ $payment->type?->label() }}</p>
                            <p class="data-row__sub">
                                <span class="data-row__amount">{{ number_format((float) $payment->amount, 0, ',', ' ') }} FCFA</span>
                                @if ($payment->method)&ensp;·&ensp;{{ $payment->method->label() }}@endif
                            </p>
                        </div>
                        <div class="data-row__right">
                            <span class="badge {{ $payment->status?->value === 'paid' ? 'badge--success' : ($payment->status?->value === 'pending' ? 'badge--warning' : '') }}">
                                {{ $payment->status?->label() }}
                            </span>
                        </div>
                    </a>
                @empty
                    <div class="empty-state" style="padding:1.25rem 0;">
                        <svg class="empty-state__icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 8.25h19.5M2.25 9h19.5m-16.5 5.25h6m-6 2.25h3m-3.75 3h15a2.25 2.25 0 0 0 2.25-2.25V6.75A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25v10.5A2.25 2.25 0 0 0 4.5 19.5Z"/></svg>
                        <p class="empty-state__title">{{ __('students.no_payment') }}</p>
                    </div>
                @endforelse
            </div>

            {{-- Historique scolaire --}}
            <div>
                <h2 class="section-heading">{{ __('students.school_history') }}</h2>
                @forelse ($student->schoolYearRecords as $record)
                    <div class="data-row" style="display:block;">
                        <div style="display:flex;align-items:center;justify-content:space-between;gap:0.75rem;">
                            <p class="data-row__title">{{ $record->schoolYear?->name ?: __('students.unknown_year') }}</p>
                            <span class="badge {{ $record->status?->value === 'active' ? 'badge--success' : '' }}">
                                {{ $record->status?->label() }}
                            </span>
                        </div>
                        <p class="data-row__sub">{{ $record->classroom_name_snapshot }} · {{ $record->level_snapshot }}</p>
                        @if ($record->final_result || $record->final_average)
                            <p class="data-row__sub" style="margin-top:0.25rem;">
                                {{ __('students.result_label') }} : <strong>{{ $record->final_result ?: '—' }}</strong>
                                &ensp;·&ensp;
                                {{ __('students.average_label') }} : <strong>{{ $record->final_average ?: '—' }}</strong>
                            </p>
                        @endif
                    </div>
                @empty
                    <div class="empty-state" style="padding:1.25rem 0;">
                        <svg class="empty-state__icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M4.26 10.147a60.438 60.438 0 0 0-.491 6.347A48.627 48.627 0 0 1 12 20.904a48.627 48.627 0 0 1 8.232-4.41 60.46 60.46 0 0 0-.491-6.347m-15.482 0a50.636 50.636 0 0 0-2.658-.813A59.906 59.906 0 0 1 12 3.493a59.903 59.903 0 0 1 10.399 5.84c-.896.248-1.783.52-2.658.814m-15.482 0A50.717 50.717 0 0 1 12 13.489a50.702 50.702 0 0 1 3.741-1.342m-7.482 0a49.99 49.99 0 0 0-1.483 1.487"/></svg>
                        <p class="empty-state__title">{{ __('students.no_history') }}</p>
                    </div>
                @endforelse
            </div>

            {{-- Notes --}}
            <div>
                <h2 class="section-heading">{{ __('students.grades') }}</h2>

                @can('create', [\App\Models\StudentSchoolGrade::class, $student])
                    <form method="POST" action="{{ route('students.grades.store', $student) }}" class="grade-add-form">
                        @csrf
                        <select name="school_year_id" class="field grade-add-form__year" required>
                            @foreach ($schoolYears as $year)
                                <option value="{{ $year->id }}">{{ $year->name }}</option>
                            @endforeach
                        </select>
                        <input name="subject"   class="field" placeholder="{{ __('homeworks.form_subject_placeholder') }}" required>
                        <input name="term"      class="field" value="{{ __('school_years.annual') }}" required>
                        <input name="grade" type="number" step="0.25" min="0" max="20" class="field" placeholder="/20" required>
                        <button class="btn-primary" style="white-space:nowrap;">{{ __('ui.add') }}</button>
                    </form>
                @endcan

                @forelse ($student->schoolGrades as $grade)
                    <div class="grade-row">
                        <div class="grade-row__left">
                            <span class="grade-row__subject">{{ $grade->subject }}</span>
                            <span class="grade-row__sep">·</span>
                            <span class="grade-row__meta">{{ $grade->schoolYear?->name }}</span>
                            <span class="grade-row__sep">·</span>
                            <span class="grade-row__meta">{{ $grade->term }}</span>
                        </div>
                        <div class="grade-row__right">
                            <span class="grade-row__score">{{ $grade->grade }}<span>/20</span></span>
                            @can('delete', $grade)
                                <form method="POST" action="{{ route('students.grades.destroy', [$student, $grade]) }}">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn-delete-grade" title="{{ __('ui.delete') }}">
                                        <svg viewBox="0 0 20 20" fill="currentColor" style="width:1rem;height:1rem;" aria-hidden="true"><path fill-rule="evenodd" d="M8.75 1A2.75 2.75 0 0 0 6 3.75v.443c-.795.077-1.584.176-2.365.298a.75.75 0 1 0 .23 1.482l.149-.022.841 10.518A2.75 2.75 0 0 0 7.596 19h4.807a2.75 2.75 0 0 0 2.742-2.53l.841-10.52.149.023a.75.75 0 0 0 .23-1.482A41.03 41.03 0 0 0 14 4.193V3.75A2.75 2.75 0 0 0 11.25 1h-2.5ZM10 4c.84 0 1.673.025 2.5.075V3.75c0-.69-.56-1.25-1.25-1.25h-2.5c-.69 0-1.25.56-1.25 1.25v.325C8.327 4.025 9.16 4 10 4ZM8.58 7.72a.75.75 0 0 0-1.5.06l.3 7.5a.75.75 0 1 0 1.5-.06l-.3-7.5Zm4.34.06a.75.75 0 1 0-1.5-.06l-.3 7.5a.75.75 0 1 0 1.5.06l.3-7.5Z" clip-rule="evenodd"/></svg>
                                    </button>
                                </form>
                            @endcan
                        </div>
                    </div>
                @empty
                    <div class="empty-state" style="padding:1.25rem 0;">
                        <svg class="empty-state__icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10"/></svg>
                        <p class="empty-state__title">{{ __('students.no_grades') }}</p>
                    </div>
                @endforelse
            </div>

            {{-- Emprunts bibliothèque --}}
            <div>
                <h2 class="section-heading">{{ __('students.library_loans') }}</h2>
                @forelse ($student->bookLoans as $loan)
                    <div class="data-row">
                        <div class="data-row__body">
                            <p class="data-row__title">{{ $loan->book?->title }}</p>
                            <p class="data-row__sub">
                                {{ $loan->borrowed_at?->format('d/m/Y') }} → {{ $loan->due_at?->format('d/m/Y') }}
                            </p>
                        </div>
                        <div class="data-row__right">
                            <span class="badge {{ $loan->returned_at ? 'badge--success' : 'badge--info' }}">
                                {{ $loan->returned_at ? __('students.returned') : __('students.in_progress') }}
                            </span>
                        </div>
                    </div>
                @empty
                    <div class="empty-state" style="padding:1.25rem 0;">
                        <svg class="empty-state__icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 0 0 6 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 0 1 6 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 0 1 6-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0 0 18 18a8.967 8.967 0 0 0-6 2.292m0-14.25v14.25"/></svg>
                        <p class="empty-state__title">{{ __('students.no_loans') }}</p>
                    </div>
                @endforelse
            </div>

        </article>
    </section>
@endsection
