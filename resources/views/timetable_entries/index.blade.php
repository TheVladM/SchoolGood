@extends('layouts.app')

@section('title', __('timetable.page_title') . ' | SchoolGood')
@section('topbar_title', __('nav.timetable'))

@section('content')
    @include('partials.page-header', [
        'title' => __('timetable.page_title'),
        'description' => __('timetable.page_desc'),
        'statLabel' => __('timetable.stat_label'),
        'statValue' => $entries->total(),
    ])

    <x-content-panel class="mt-6" data-filter-scope :title="__('timetable.grid')" :subtitle="__('timetable.grid_subtitle')">
        <x-slot:toolbar>
            <div class="content-panel__toolbar">
                <label class="search-shell">
                    <span class="search-shell__label">{{ __('timetable.search_label') }}</span>
                    <input type="search" class="field min-w-[18rem]" placeholder="{{ __('timetable.search_placeholder') }}" data-table-search>
                </label>

                @can('create', \App\Models\TimetableEntry::class)
                    <a href="{{ route('timetable-entries.create') }}" class="btn-primary self-end">
                        <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
                        {{ __('timetable.new_slot') }}
                    </a>
                @endcan
            </div>
        </x-slot:toolbar>

        <div class="overflow-x-auto table-shell">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>{{ __('timetable.col_level') }}</th>
                        <th>{{ __('timetable.col_section') }}</th>
                        <th>{{ __('timetable.col_subject') }}</th>
                        <th>{{ __('timetable.col_day') }}</th>
                        <th>{{ __('timetable.col_schedule') }}</th>
                        <th class="text-right">{{ __('ui.actions_col') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($entries as $entry)
                        <tr data-filterable-row>
                            <td class="font-semibold text-slate-900">{{ $entry->level }}</td>
                            <td>
                                <span class="badge {{ $entry->section?->value === 'anglophone' ? 'badge--violet' : 'badge--teal' }}">
                                    {{ $entry->section?->label() }}
                                </span>
                            </td>
                            <td class="text-slate-700">{{ $entry->subject }}</td>
                            <td><span class="badge badge--amber">{{ $entry->day?->value }}</span></td>
                            <td class="text-slate-600 text-sm font-mono">{{ substr($entry->start_time, 0, 5) }} – {{ substr($entry->end_time, 0, 5) }}</td>
                            <td>
                                <div class="record-actions justify-end">
                                    <a href="{{ route('timetable-entries.show', $entry) }}" class="btn-secondary">{{ __('ui.view') }}</a>
                                    @can('update', $entry)
                                        <a href="{{ route('timetable-entries.edit', $entry) }}" class="btn-secondary">{{ __('ui.edit') }}</a>
                                    @endcan
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6">
                                <div class="empty-state">
                                    <svg class="empty-state__icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5"/></svg>
                                    <p class="empty-state__title">{{ __('timetable.empty_title') }}</p>
                                    <p class="empty-state__desc">{{ __('timetable.empty_desc') }}</p>
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
            <p class="empty-state__desc">{{ __('timetable.no_match_desc') }}</p>
        </div>

        <div class="mt-6 pagination-wrap">
            {{ $entries->links() }}
        </div>
    </x-content-panel>
@endsection
