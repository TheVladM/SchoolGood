@extends('layouts.app')

@section('title', __('courses.page_title') . ' | SchoolGood')
@section('topbar_title', __('nav.courses'))

@section('content')
    @include('partials.page-header', [
        'title' => __('courses.page_title'),
        'description' => __('courses.page_desc'),
        'statLabel' => __('ui.total'),
        'statValue' => $courses->total(),
    ])

    <x-content-panel class="mt-6" data-filter-scope :title="__('courses.catalog')" :subtitle="__('courses.catalog_subtitle')">
        <x-slot:toolbar>
            <div class="content-panel__toolbar">
                <label class="search-shell">
                    <span class="search-shell__label">{{ __('courses.search_label') }}</span>
                    <input type="search" class="field min-w-[18rem]" placeholder="{{ __('courses.search_placeholder') }}" data-table-search>
                </label>

                @can('create', \App\Models\Course::class)
                    <a href="{{ route('courses.create') }}" class="btn-primary self-end">
                        <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
                        {{ __('courses.new_course') }}
                    </a>
                @endcan
            </div>
        </x-slot:toolbar>

        <div class="overflow-x-auto table-shell">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>{{ __('courses.col_course') }}</th>
                        <th>{{ __('courses.col_classroom') }}</th>
                        <th>{{ __('courses.col_teacher') }}</th>
                        <th>{{ __('courses.col_day') }}</th>
                        <th class="text-right">{{ __('ui.actions_col') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($courses as $course)
                        <tr data-filterable-row>
                            <td class="font-semibold text-slate-900">{{ $course->title }}</td>
                            <td class="text-slate-600">{{ $course->classroom?->name ?? '—' }}</td>
                            <td class="text-slate-600">{{ $course->teacher?->name ?? '—' }}</td>
                            <td><span class="badge badge--info">{{ $course->day?->value }}</span></td>
                            <td>
                                <div class="record-actions justify-end">
                                    <a href="{{ route('courses.show', $course) }}" class="btn-secondary">{{ __('ui.view') }}</a>
                                    @can('update', $course)
                                        <a href="{{ route('courses.edit', $course) }}" class="btn-secondary">{{ __('ui.edit') }}</a>
                                    @endcan
                                    @can('delete', $course)
                                        <form method="POST" action="{{ route('courses.destroy', $course) }}">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn-danger" onclick="return confirm('{{ __('courses.delete_confirm') }}')">
                                                {{ __('ui.delete') }}
                                            </button>
                                        </form>
                                    @endcan
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5">
                                <div class="empty-state">
                                    <svg class="empty-state__icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 0 0 6 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 0 1 6 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 0 1 6-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0 0 18 18a8.967 8.967 0 0 0-6 2.292m0-14.25v14.25"/></svg>
                                    <p class="empty-state__title">{{ __('courses.empty_title') }}</p>
                                    <p class="empty-state__desc">{{ __('courses.empty_desc') }}</p>
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
            <p class="empty-state__desc">{{ __('courses.no_match_desc') }}</p>
        </div>

        <div class="mt-6 pagination-wrap">
            {{ $courses->links() }}
        </div>
    </x-content-panel>
@endsection
