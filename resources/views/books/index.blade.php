@extends('layouts.app')

@section('title', __('books.page_title') . ' | SchoolGood')
@section('topbar_title', __('nav.library'))

@section('content')
    @include('partials.page-header', [
        'title' => __('books.page_title'),
        'description' => __('books.page_desc'),
        'statLabel' => __('books.stat_label'),
        'statValue' => $books->total(),
    ])

    <x-content-panel class="mt-6" data-filter-scope :title="__('books.catalog')" :subtitle="__('books.catalog_subtitle')">
        <x-slot:toolbar>
            <div class="content-panel__toolbar">
                <label class="search-shell">
                    <span class="search-shell__label">{{ __('books.search_label') }}</span>
                    <input type="search" class="field min-w-[18rem]" placeholder="{{ __('books.search_placeholder') }}" data-table-search>
                </label>

                @can('create', \App\Models\Book::class)
                    <a href="{{ route('books.create') }}" class="btn-primary self-end">
                        <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
                        {{ __('books.new_book') }}
                    </a>
                @endcan
            </div>
        </x-slot:toolbar>

        <div class="overflow-x-auto table-shell">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>{{ __('books.col_book') }}</th>
                        <th>{{ __('books.col_author') }}</th>
                        <th>{{ __('books.col_stock') }}</th>
                        <th>{{ __('books.col_available') }}</th>
                        <th>{{ __('books.col_shelf') }}</th>
                        <th class="text-right">{{ __('ui.actions_col') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($books as $book)
                        @php $available = $book->availableCopies(); @endphp
                        <tr data-filterable-row>
                            <td>
                                <div class="table-name-cell">
                                    <span class="avatar avatar--amber">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" style="width:1rem;height:1rem;" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 0 0 6 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 0 1 6 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 0 1 6-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0 0 18 18a8.967 8.967 0 0 0-6 2.292m0-14.25v14.25"/></svg>
                                    </span>
                                    <span class="font-semibold text-slate-900">{{ $book->title }}</span>
                                </div>
                            </td>
                            <td class="text-slate-600">{{ $book->author ?: '—' }}</td>
                            <td class="text-slate-700">{{ $book->total_copies }}</td>
                            <td>
                                <span class="badge {{ $available > 0 ? 'badge--success' : 'badge--danger' }}">
                                    {{ $available }}
                                </span>
                            </td>
                            <td class="text-slate-600">{{ $book->shelf_location ?: '—' }}</td>
                            <td>
                                <div class="record-actions justify-end">
                                    <a href="{{ route('books.show', $book) }}" class="btn-secondary">{{ __('ui.view') }}</a>
                                    @can('update', $book)
                                        <a href="{{ route('books.edit', $book) }}" class="btn-secondary">{{ __('ui.edit') }}</a>
                                    @endcan
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6">
                                <div class="empty-state">
                                    <svg class="empty-state__icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 0 0 6 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 0 1 6 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 0 1 6-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0 0 18 18a8.967 8.967 0 0 0-6 2.292m0-14.25v14.25"/></svg>
                                    <p class="empty-state__title">{{ __('books.empty_title') }}</p>
                                    <p class="empty-state__desc">{{ __('books.empty_desc') }}</p>
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
            <p class="empty-state__desc">{{ __('books.no_match_desc') }}</p>
        </div>

        <div class="mt-6 pagination-wrap">
            {{ $books->links() }}
        </div>
    </x-content-panel>
@endsection
