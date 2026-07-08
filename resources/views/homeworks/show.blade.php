@extends('layouts.app')

@section('title', $homework->title . ' | SchoolGood')
@section('topbar_title', __('homeworks.show_title'))

@section('content')
    @include('partials.page-header', [
        'title' => $homework->title,
        'description' => ($homework->subject ?? __('homeworks.page_title')) . ' · ' . $homework->classroom->name . ' · ' . __('homeworks.due_prefix') . ' ' . $homework->due_date->format('d/m/Y'),
    ])

    <div class="mt-6 grid gap-6 lg:grid-cols-3" data-reveal>
        <div class="lg:col-span-2 space-y-6">
            <x-content-panel :title="__('homeworks.instructions')" :subtitle="__('homeworks.instructions_sub')">
                @if ($homework->description)
                    <div class="prose prose-slate max-w-none whitespace-pre-wrap text-slate-700">{{ $homework->description }}</div>
                @else
                    <p class="text-slate-500">{{ __('homeworks.no_description') }}</p>
                @endif

                <div class="mt-4 flex flex-wrap gap-3">
                    @can('update', $homework)
                        <a href="{{ route('homeworks.edit', $homework) }}" class="btn-primary">{{ __('ui.edit') }}</a>
                    @endcan
                    <a href="{{ route('homeworks.index') }}" class="btn-secondary">{{ __('ui.back') }}</a>
                </div>
            </x-content-panel>

            <x-content-panel :title="__('homeworks.tracking')" :subtitle="__('homeworks.tracking_sub')">
                <div class="table-shell">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>{{ __('homeworks.col_student') }}</th>
                                <th>{{ __('ui.status_col') }}</th>
                                <th>{{ __('homeworks.col_grade') }}</th>
                                <th class="text-right">{{ __('ui.actions_col') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($homework->submissions as $submission)
                                <tr>
                                    <td class="font-semibold text-slate-900">{{ $submission->student->full_name }}</td>
                                    <td><span class="badge">{{ $submission->status->label() }}</span></td>
                                    <td>{{ $submission->grade !== null ? number_format((float) $submission->grade, 2, ',', ' ') . ' / 20' : '-' }}</td>
                                    <td>
                                        <div class="record-actions justify-end">
                                            @if ($submission->file_path)
                                                <a href="{{ asset('storage/' . $submission->file_path) }}" class="btn-secondary" target="_blank" rel="noopener">{{ __('homeworks.file_btn') }}</a>
                                            @endif

                                            @can('update', $homework)
                                                @if ($submission->status->value === 'submitted' || $submission->status->value === 'graded')
                                                    <form method="POST" action="{{ route('homeworks.submissions.grade', [$homework, $submission]) }}" class="inline-flex flex-wrap items-center gap-2">
                                                        @csrf
                                                        <input type="number" name="grade" min="0" max="20" step="0.25" class="field w-20" placeholder="{{ __('homeworks.grade_placeholder') }}" value="{{ old('grade', $submission->grade) }}" required>
                                                        <button type="submit" class="btn-primary">{{ __('homeworks.grade_btn') }}</button>
                                                    </form>
                                                @endif
                                            @endcan

                                            @if (auth()->user()->hasRole(\App\Enums\UserRole::Parent) && $parentChildren->contains($submission->student_id) && $submission->status->value === 'pending')
                                                <form method="POST" action="{{ route('homeworks.submissions.store', $homework) }}" enctype="multipart/form-data" class="inline-flex flex-wrap items-center gap-2">
                                                    @csrf
                                                    <input type="hidden" name="student_id" value="{{ $submission->student_id }}">
                                                    <input type="file" name="file" class="field text-sm max-w-[12rem]">
                                                    <button type="submit" class="btn-primary">{{ __('homeworks.submit_btn') }}</button>
                                                </form>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </x-content-panel>
        </div>

        <aside>
            <x-content-panel :title="__('homeworks.info_panel')" :subtitle="__('homeworks.info_sub')">
                <div class="info-list">
                    <div class="info-row">
                        <span class="info-key">{{ __('homeworks.info_teacher') }}</span>
                        <span class="info-val">{{ $homework->teacher->name }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-key">{{ __('homeworks.info_classroom') }}</span>
                        <span class="info-val">{{ $homework->classroom->name }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-key">{{ __('homeworks.info_due') }}</span>
                        <span class="info-val {{ $homework->isOverdue() ? 'text-rose-600 font-semibold' : 'text-emerald-700' }}">
                            {{ $homework->due_date->format('d/m/Y H:i') }}
                        </span>
                    </div>
                    <div class="info-row">
                        <span class="info-key">{{ __('homeworks.info_submissions') }}</span>
                        <span class="info-val">
                            {{ $homework->submissions->whereIn('status', ['submitted', 'graded'])->count() }}
                            / {{ $homework->submissions->count() }}
                        </span>
                    </div>
                </div>
            </x-content-panel>
        </aside>
    </div>
@endsection
