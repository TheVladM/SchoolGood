@extends('layouts.app')

@section('title', __('school_years.page_title') . ' | SchoolGood')
@section('topbar_title', __('nav.school_years'))

@section('content')
    @include('partials.page-header', [
        'title' => __('school_years.page_title'),
        'description' => __('school_years.page_desc'),
        'statLabel' => __('school_years.stat_label'),
        'statValue' => $schoolYears->total(),
    ])

    <x-content-panel class="mt-6" data-filter-scope :title="__('school_years.registry')" :subtitle="__('school_years.registry_subtitle')">
        <x-slot:toolbar>
            <div class="content-panel__toolbar">
                <label class="search-shell">
                    <span class="search-shell__label">{{ __('school_years.search_label') }}</span>
                    <input type="search" class="field min-w-[18rem]" placeholder="{{ __('school_years.search_placeholder') }}" data-table-search>
                </label>

                <a href="{{ route('school-years.create') }}" class="btn-primary self-end">
                    <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
                    {{ __('school_years.new_year') }}
                </a>
            </div>
        </x-slot:toolbar>

        <div class="overflow-x-auto table-shell">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>{{ __('school_years.col_year') }}</th>
                        <th>{{ __('school_years.col_period') }}</th>
                        <th>{{ __('ui.status_col') }}</th>
                        <th>{{ __('school_years.col_next') }}</th>
                        <th>{{ __('school_years.col_records') }}</th>
                        <th class="text-right">{{ __('ui.actions_col') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($schoolYears as $schoolYear)
                        @php
                            $statusBadge = match ($schoolYear->status?->value) {
                                'planned' => 'badge--info',
                                'current' => 'badge--success',
                                default   => '',
                            };
                        @endphp
                        <tr data-filterable-row>
                            <td class="font-semibold text-slate-900">{{ $schoolYear->name }}</td>
                            <td class="text-slate-600 text-sm font-mono">
                                {{ $schoolYear->starts_on?->format('d/m/Y') }} – {{ $schoolYear->ends_on?->format('d/m/Y') }}
                            </td>
                            <td>
                                <span class="badge {{ $statusBadge }}">{{ $schoolYear->status?->label() }}</span>
                            </td>
                            <td class="text-slate-600">{{ $schoolYear->nextSchoolYear?->name ?: '—' }}</td>
                            <td class="font-semibold text-slate-900">{{ $schoolYear->student_records_count }}</td>
                            <td>
                                <div class="record-actions justify-end">
                                    <a href="{{ route('school-years.show', $schoolYear) }}" class="btn-secondary">{{ __('ui.view') }}</a>
                                    <a href="{{ route('school-years.edit', $schoolYear) }}" class="btn-secondary">{{ __('ui.edit') }}</a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6">
                                <div class="empty-state">
                                    <svg class="empty-state__icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5m-9-6h.008v.008H12v-.008ZM12 15h.008v.008H12V15Zm0 2.25h.008v.008H12v-.008ZM9.75 15h.008v.008H9.75V15Zm0 2.25h.008v.008H9.75v-.008ZM7.5 15h.008v.008H7.5V15Zm0 2.25h.008v.008H7.5v-.008Zm6.75-4.5h.008v.008h-.008v-.008Zm0 2.25h.008v.008h-.008V15Zm0 2.25h.008v.008h-.008v-.008Zm2.25-4.5h.008v.008H16.5v-.008Zm0 2.25h.008v.008H16.5V15Z"/></svg>
                                    <p class="empty-state__title">{{ __('school_years.empty_title') }}</p>
                                    <p class="empty-state__desc">{{ __('school_years.empty_desc') }}</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="empty-state mt-4" data-filter-empty hidden>
            <svg class="empty-state__icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z"/></svg>
            <p class="empty-state__title">{{ __('ui.empty_title') }}</p>
            <p class="empty-state__desc">{{ __('school_years.no_match_desc') }}</p>
        </div>

        <div class="mt-6 pagination-wrap">
            {{ $schoolYears->links() }}
        </div>
    </x-content-panel>
@endsection
