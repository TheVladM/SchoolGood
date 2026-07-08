@extends('layouts.app')

@section('title', __('announcements.page_title') . ' | SchoolGood')
@section('topbar_title', __('nav.messages'))

@section('content')
    @include('partials.page-header', [
        'title' => __('announcements.page_title'),
        'description' => __('announcements.page_desc'),
        'statLabel' => __('announcements.stat_label'),
        'statValue' => $announcements->total(),
    ])

    <x-content-panel class="mt-6" data-filter-scope :title="__('announcements.registry')" :subtitle="__('announcements.registry_subtitle')">
        <x-slot:toolbar>
            <div class="content-panel__toolbar">
                <label class="search-shell">
                    <span class="search-shell__label">{{ __('announcements.search_label') }}</span>
                    <input type="search" class="field min-w-[18rem]" placeholder="{{ __('announcements.search_placeholder') }}" data-table-search>
                </label>

                @if (auth()->user()->hasRole(\App\Enums\UserRole::Founder) && ($pendingCount ?? 0) > 0)
                    <a href="{{ route('announcements.index', ['filter' => 'pending']) }}" class="btn-secondary self-end">
                        <span class="badge badge--warning">{{ $pendingCount }}</span>
                        {{ __('announcements.pending_btn') }}
                    </a>
                @endif

                @can('create', \App\Models\Announcement::class)
                    <a href="{{ route('announcements.create') }}" class="btn-primary self-end">
                        <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
                        {{ __('announcements.new_message') }}
                    </a>
                @endcan
            </div>
        </x-slot:toolbar>

        <div class="overflow-x-auto table-shell">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>{{ __('announcements.col_title') }}</th>
                        <th>{{ __('announcements.col_audience') }}</th>
                        <th>{{ __('announcements.col_classroom') }}</th>
                        <th>{{ __('announcements.col_author') }}</th>
                        <th>{{ __('ui.status_col') }}</th>
                        <th class="text-right">{{ __('ui.actions_col') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($announcements as $announcement)
                        @php
                            $statusBadge = match ($announcement->status?->value) {
                                'approved'         => 'badge--success',
                                'pending_approval' => 'badge--warning',
                                'rejected'         => 'badge--danger',
                                default            => '',
                            };
                        @endphp
                        <tr data-filterable-row>
                            <td class="font-semibold text-slate-900">{{ $announcement->title }}</td>
                            <td class="text-slate-600">{{ $announcement->audience?->label() }}</td>
                            <td class="text-slate-600">{{ $announcement->classroom?->name ?: __('announcements.all_classrooms') }}</td>
                            <td class="text-slate-600">{{ $announcement->author?->name }}</td>
                            <td>
                                <span class="badge {{ $statusBadge }}">{{ $announcement->status?->label() }}</span>
                            </td>
                            <td>
                                <div class="record-actions justify-end">
                                    <a href="{{ route('announcements.show', $announcement) }}" class="btn-secondary">{{ __('ui.view') }}</a>
                                    @if (
                                        auth()->user()->hasRole(\App\Enums\UserRole::Founder) ||
                                        auth()->id() === $announcement->author_id
                                    )
                                        <a href="{{ route('announcements.edit', $announcement) }}" class="btn-secondary">{{ __('ui.edit') }}</a>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6">
                                <div class="empty-state">
                                    <svg class="empty-state__icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 0 1-2.25 2.25h-15a2.25 2.25 0 0 1-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25m19.5 0v.243a2.25 2.25 0 0 1-1.07 1.916l-7.5 4.615a2.25 2.25 0 0 1-2.36 0L3.32 8.91a2.25 2.25 0 0 1-1.07-1.916V6.75"/></svg>
                                    <p class="empty-state__title">{{ __('announcements.empty_title') }}</p>
                                    <p class="empty-state__desc">{{ __('announcements.empty_desc') }}</p>
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
            <p class="empty-state__desc">{{ __('announcements.no_match_desc') }}</p>
        </div>

        <div class="mt-6 pagination-wrap">
            {{ $announcements->links() }}
        </div>
    </x-content-panel>
@endsection
