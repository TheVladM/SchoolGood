@extends('layouts.app')

@section('title', __('book_loans.page_title') . ' | SchoolGood')
@section('topbar_title', __('nav.book_loans'))

@section('content')
    @include('partials.page-header', [
        'title' => __('book_loans.page_title'),
        'description' => __('book_loans.page_desc'),
        'statLabel' => __('book_loans.stat_label'),
        'statValue' => $loans->total(),
    ])

    <x-content-panel class="mt-6" data-filter-scope :title="__('book_loans.registry')" :subtitle="__('book_loans.registry_subtitle')">
        <x-slot:toolbar>
            <div class="content-panel__toolbar">
                <label class="search-shell">
                    <span class="search-shell__label">{{ __('book_loans.search_label') }}</span>
                    <input type="search" class="field min-w-[18rem]" placeholder="{{ __('book_loans.search_placeholder') }}" data-table-search>
                </label>

                @can('create', \App\Models\BookLoan::class)
                    <a href="{{ route('book-loans.create') }}" class="btn-primary self-end">
                        <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
                        {{ __('book_loans.new_loan') }}
                    </a>
                @endcan
            </div>
        </x-slot:toolbar>

        <div class="overflow-x-auto table-shell">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>{{ __('book_loans.col_book') }}</th>
                        <th>{{ __('book_loans.col_borrower') }}</th>
                        <th>{{ __('book_loans.col_borrowed') }}</th>
                        <th>{{ __('book_loans.col_due') }}</th>
                        <th>{{ __('book_loans.col_status') }}</th>
                        <th class="text-right">{{ __('ui.actions_col') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($loans as $loan)
                        @php
                            $isOverdue = !$loan->returned_at && $loan->due_at?->isPast();
                            $loanBadge = $loan->returned_at ? 'badge--success' : ($isOverdue ? 'badge--danger' : 'badge--info');
                            $loanLabel = $loan->returned_at ? __('book_loans.returned') : ($isOverdue ? __('book_loans.overdue') : __('book_loans.in_progress'));
                        @endphp
                        <tr data-filterable-row>
                            <td class="font-semibold text-slate-900">{{ $loan->book?->title }}</td>
                            <td class="text-slate-600">{{ $loan->borrower_name }}</td>
                            <td class="text-slate-600 text-sm">{{ $loan->borrowed_at?->format('d/m/Y') }}</td>
                            <td class="{{ $isOverdue ? 'text-rose-600 font-medium' : 'text-slate-600' }} text-sm">
                                {{ $loan->due_at?->format('d/m/Y') }}
                            </td>
                            <td><span class="badge {{ $loanBadge }}">{{ $loanLabel }}</span></td>
                            <td>
                                <div class="record-actions justify-end">
                                    <a href="{{ route('book-loans.show', $loan) }}" class="btn-secondary">{{ __('ui.view') }}</a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6">
                                <div class="empty-state">
                                    <svg class="empty-state__icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 0 0 6 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 0 1 6 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 0 1 6-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0 0 18 18a8.967 8.967 0 0 0-6 2.292m0-14.25v14.25"/></svg>
                                    <p class="empty-state__title">{{ __('book_loans.empty_title') }}</p>
                                    <p class="empty-state__desc">{{ __('book_loans.empty_desc') }}</p>
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
            <p class="empty-state__desc">{{ __('book_loans.no_match_desc') }}</p>
        </div>

        <div class="mt-6 pagination-wrap">
            {{ $loans->links() }}
        </div>
    </x-content-panel>
@endsection
