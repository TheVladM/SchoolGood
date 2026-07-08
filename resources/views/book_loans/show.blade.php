@extends('layouts.app')

@section('title', $loan->book?->title.' | SchoolGood')
@section('topbar_title', __('nav.book_loans'))

@section('content')
    @php
        $isOverdue  = !$loan->returned_at && $loan->due_at?->isPast();
        $loanBadge  = $loan->returned_at ? 'badge--success' : ($isOverdue ? 'badge--danger' : 'badge--info');
        $loanLabel  = $loan->returned_at ? __('book_loans.returned') : ($isOverdue ? __('book_loans.overdue') : __('book_loans.in_progress'));
    @endphp

    @include('partials.page-header', ['title' => $loan->book?->title, 'description' => $loan->borrower_name])

    <section class="mt-6 grid gap-6 lg:grid-cols-[0.9fr_1.1fr]" data-reveal>

        {{-- Détails de l'emprunt --}}
        <article class="surface-card p-5 lg:p-6">
            <div class="entity-header">
                <div class="entity-header__icon" style="background:#fef3c7;color:#b45309;border-radius:14px;">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" style="width:1.4rem;height:1.4rem;" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 0 0 6 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 0 1 6 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 0 1 6-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0 0 18 18a8.967 8.967 0 0 0-6 2.292m0-14.25v14.25"/></svg>
                </div>
                <div class="flex-1">
                    <p class="entity-header__name">{{ $loan->book?->title }}</p>
                    <p class="entity-header__meta">{{ $loan->borrower_name }}</p>
                </div>
                <span class="badge {{ $loanBadge }}">{{ $loanLabel }}</span>
            </div>

            <div class="info-list">
                <div class="info-row">
                    <span class="info-key">{{ __('book_loans.info_borrower') }}</span>
                    <span class="info-val">{{ $loan->borrower_name }}</span>
                </div>
                <div class="info-row">
                    <span class="info-key">{{ __('book_loans.info_borrowed_at') }}</span>
                    <span class="info-val">{{ $loan->borrowed_at?->format('d/m/Y') ?: '—' }}</span>
                </div>
                <div class="info-row">
                    <span class="info-key">{{ __('book_loans.info_due_at') }}</span>
                    <span class="info-val {{ $isOverdue ? 'text-rose-600 font-semibold' : '' }}">
                        {{ $loan->due_at?->format('d/m/Y') ?: '—' }}
                    </span>
                </div>
                <div class="info-row">
                    <span class="info-key">{{ __('book_loans.info_returned_at') }}</span>
                    <span class="info-val {{ $loan->returned_at ? 'text-emerald-700' : '' }}">
                        {{ $loan->returned_at?->format('d/m/Y') ?: '—' }}
                    </span>
                </div>
                <div class="info-row">
                    <span class="info-key">{{ __('book_loans.info_penalty_day') }}</span>
                    <span class="info-val">{{ number_format((float) $loan->daily_penalty_rate, 0, ',', ' ') }} FCFA</span>
                </div>
                @if ($loan->penaltyAmount() > 0)
                    <div class="info-row">
                        <span class="info-key">{{ __('book_loans.info_penalty_now') }}</span>
                        <span class="info-val text-rose-600 font-semibold">
                            {{ number_format($loan->penaltyAmount(), 0, ',', ' ') }} FCFA
                        </span>
                    </div>
                @endif
                @if ($loan->notes)
                    <div class="info-row items-start">
                        <span class="info-key pt-0.5">{{ __('book_loans.info_notes') }}</span>
                        <span class="info-val">{{ $loan->notes }}</span>
                    </div>
                @endif
            </div>

            <div class="mt-6 flex flex-wrap gap-3">
                <a href="{{ route('book-loans.index') }}" class="btn-secondary">{{ __('ui.back') }}</a>

                @if (auth()->user()->hasAnyRole([\App\Enums\UserRole::Founder, \App\Enums\UserRole::Admin, \App\Enums\UserRole::Scolarite]))
                    <a href="{{ route('book-loans.edit', $loan) }}" class="btn-primary">{{ __('ui.edit') }}</a>

                    @if (! $loan->returned_at)
                        <form method="POST" action="{{ route('book-loans.return', $loan) }}">
                            @csrf
                            <button type="submit" class="btn-primary">{{ __('book_loans.record_return') }}</button>
                        </form>
                    @endif
                @endif
            </div>
        </article>

        {{-- Livre et traitement --}}
        <article class="surface-card p-5 lg:p-6 space-y-6">
            <div>
                <h2 class="section-heading">{{ __('book_loans.book_section') }}</h2>
                <div class="rounded-xl border border-slate-100 p-4">
                    <p class="font-semibold text-slate-900">{{ $loan->book?->title }}</p>
                    <p class="mt-0.5 text-sm text-slate-500">{{ $loan->book?->author ?: '—' }}</p>
                    <p class="mt-1 text-xs text-slate-400">{{ __('book_loans.shelf_label') }} : {{ $loan->book?->shelf_location ?: '—' }}</p>
                </div>
            </div>

            <div>
                <h2 class="section-heading">{{ __('book_loans.processing') }}</h2>
                <div class="info-list">
                    <div class="info-row">
                        <span class="info-key">{{ __('book_loans.issued_by') }}</span>
                        <span class="info-val">{{ $loan->issuedBy?->name ?: '—' }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-key">{{ __('book_loans.returned_by') }}</span>
                        <span class="info-val">{{ $loan->returnedBy?->name ?: '—' }}</span>
                    </div>
                </div>
            </div>
        </article>
    </section>
@endsection
