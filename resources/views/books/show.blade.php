@extends('layouts.app')

@section('title', $book->title.' | SchoolGood')
@section('topbar_title', __('nav.library'))

@section('content')
    @include('partials.page-header', ['title' => $book->title, 'description' => $book->author])

    <section class="mt-6 detail-grid" data-reveal>

        {{-- Détails du livre --}}
        <article class="surface-card p-5 lg:p-6">
            <div class="entity-header">
                <div class="entity-header__icon" style="background:#fef3c7;color:#b45309;border-radius:14px;">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" style="width:1.4rem;height:1.4rem;" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 0 0 6 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 0 1 6 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 0 1 6-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0 0 18 18a8.967 8.967 0 0 0-6 2.292m0-14.25v14.25"/></svg>
                </div>
                <div class="flex-1">
                    <p class="entity-header__name">{{ $book->title }}</p>
                    <p class="entity-header__meta">{{ $book->author ?: '—' }}</p>
                </div>
                @if ($book->category)
                    <span class="badge badge--amber">{{ $book->category }}</span>
                @endif
            </div>

            <div class="info-list">
                <div class="info-row">
                    <span class="info-key">{{ __('books.info_author') }}</span>
                    <span class="info-val">{{ $book->author ?: '—' }}</span>
                </div>
                <div class="info-row">
                    <span class="info-key">{{ __('books.info_isbn') }}</span>
                    <span class="info-val font-mono text-xs">{{ $book->isbn ?: '—' }}</span>
                </div>
                <div class="info-row">
                    <span class="info-key">{{ __('books.info_language') }}</span>
                    <span class="info-val">{{ $book->language ?: '—' }}</span>
                </div>
                <div class="info-row">
                    <span class="info-key">{{ __('books.info_stock') }}</span>
                    <span class="info-val font-semibold">{{ $book->total_copies }}</span>
                </div>
                <div class="info-row">
                    <span class="info-key">{{ __('books.info_available') }}</span>
                    <span class="info-val">
                        <span class="badge {{ $book->availableCopies() > 0 ? 'badge--success' : 'badge--danger' }}">
                            {{ $book->availableCopies() }}
                        </span>
                    </span>
                </div>
                <div class="info-row">
                    <span class="info-key">{{ __('books.info_shelf') }}</span>
                    <span class="info-val">{{ $book->shelf_location ?: '—' }}</span>
                </div>
                <div class="info-row">
                    <span class="info-key">{{ __('books.info_penalty') }}</span>
                    <span class="info-val">{{ number_format((float) $book->late_fee_per_day, 0, ',', ' ') }} FCFA</span>
                </div>
                @if ($book->description)
                    <div class="info-row items-start">
                        <span class="info-key pt-0.5">{{ __('books.info_description') }}</span>
                        <span class="info-val">{{ $book->description }}</span>
                    </div>
                @endif
            </div>

            <div class="mt-6 flex gap-3">
                <a href="{{ route('books.index') }}" class="btn-secondary">{{ __('ui.back') }}</a>
                @if (auth()->user()->hasAnyRole([\App\Enums\UserRole::Founder, \App\Enums\UserRole::Admin, \App\Enums\UserRole::Scolarite]))
                    <a href="{{ route('books.edit', $book) }}" class="btn-primary">{{ __('ui.edit') }}</a>
                @endif
            </div>
        </article>

        {{-- Historique des emprunts --}}
        <article class="surface-card p-5 lg:p-6">
            <h2 class="section-heading">{{ __('books.loan_history') }}</h2>
            @forelse ($book->loans as $loan)
                <div class="rounded-xl border border-slate-100 p-3 mb-2 last:mb-0">
                    <div class="flex items-center justify-between gap-3">
                        <p class="font-semibold text-slate-900 text-sm">{{ $loan->borrower_name }}</p>
                        <span class="badge {{ $loan->returned_at ? 'badge--success' : 'badge--info' }}">
                            {{ $loan->returned_at ? __('books.returned') : __('books.in_progress') }}
                        </span>
                    </div>
                    <p class="mt-0.5 text-xs text-slate-500">
                        {{ $loan->borrowed_at?->format('d/m/Y') }} – {{ $loan->due_at?->format('d/m/Y') }}
                    </p>
                    @if ($loan->penaltyAmount() > 0)
                        <p class="mt-0.5 text-xs text-rose-600">
                            {{ __('books.penalty_label') }} : {{ number_format($loan->penaltyAmount(), 0, ',', ' ') }} FCFA
                        </p>
                    @endif
                </div>
            @empty
                <div class="empty-state py-6">
                    <svg class="empty-state__icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 0 0 2.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 0 0-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 0 0 .75-.75 2.25 2.25 0 0 0-.1-.664m-5.8 0A2.251 2.251 0 0 1 13.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25ZM6.75 12h.008v.008H6.75V12Zm0 3h.008v.008H6.75V15Zm0 3h.008v.008H6.75V18Z"/></svg>
                    <p class="empty-state__title">{{ __('books.no_loans') }}</p>
                    <p class="empty-state__desc">{{ __('books.no_loans_desc') }}</p>
                </div>
            @endforelse
        </article>
    </section>
@endsection
