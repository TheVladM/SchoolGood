@extends('layouts.app')

@section('title', __('payments.page_title') . ' | SchoolGood')
@section('topbar_title', __('nav.payments'))

@section('content')
    @include('partials.page-header', [
        'title' => __('payments.page_title'),
        'description' => __('payments.page_desc'),
        'statLabel' => __('payments.stat_label'),
        'statValue' => $payments->total(),
    ])

    @if (($pendingValidationCount ?? 0) > 0)
        <div class="alert--warning mt-4" data-reveal>
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" style="width:1.1rem;height:1.1rem;flex-shrink:0;" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126ZM12 15.75h.007v.008H12v-.008Z"/></svg>
            <strong>{{ $pendingValidationCount }}</strong> {{ __('payments.pending_alert') }}
        </div>
    @endif

    <x-content-panel class="mt-6" data-filter-scope :title="__('payments.registry')" :subtitle="__('payments.registry_subtitle')">
        <x-slot:toolbar>
            <div class="content-panel__toolbar">
                <label class="search-shell">
                    <span class="search-shell__label">{{ __('payments.search_label') }}</span>
                    <input type="search" class="field min-w-[18rem]" placeholder="{{ __('payments.search_placeholder') }}" data-table-search>
                </label>

                @if (auth()->user()->hasAnyRole([\App\Enums\UserRole::Founder, \App\Enums\UserRole::Scolarite]))
                    <a href="{{ route('payments.create') }}" class="btn-primary self-end">
                        <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
                        {{ __('payments.new_payment') }}
                    </a>
                @endif
            </div>
        </x-slot:toolbar>

        <div class="overflow-x-auto table-shell">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>{{ __('payments.col_student') }}</th>
                        <th>{{ __('payments.col_type') }}</th>
                        <th>{{ __('payments.col_amount') }}</th>
                        <th>{{ __('payments.col_method') }}</th>
                        <th>{{ __('payments.col_reference') }}</th>
                        <th>{{ __('ui.status_col') }}</th>
                        <th class="text-right">{{ __('ui.actions_col') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($payments as $payment)
                        <tr data-filterable-row>
                            <td class="font-semibold text-slate-900">{{ $payment->student?->full_name }}</td>
                            <td class="text-slate-600">{{ $payment->type?->label() }}</td>
                            <td class="font-semibold text-slate-900">{{ number_format((float) $payment->amount, 0, ',', ' ') }} FCFA</td>
                            <td class="text-slate-600">{{ $payment->method?->label() }}</td>
                            <td class="text-slate-500 text-sm font-mono">{{ $payment->reference ?: '—' }}</td>
                            <td>
                                <span class="badge {{ $payment->status?->value === 'paid' ? 'badge--success' : 'badge--warning' }}">
                                    {{ $payment->status?->label() }}
                                </span>
                            </td>
                            <td>
                                <div class="record-actions justify-end">
                                    <a href="{{ route('payments.show', $payment) }}" class="btn-secondary">{{ __('ui.view') }}</a>
                                    @if (auth()->user()->hasAnyRole([\App\Enums\UserRole::Founder, \App\Enums\UserRole::Scolarite]))
                                        <a href="{{ route('payments.edit', $payment) }}" class="btn-secondary">{{ __('ui.edit') }}</a>
                                        <form method="POST" action="{{ route('payments.destroy', $payment) }}">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn-danger" onclick="return confirm(&quot;{{ __('payments.delete_confirm') }}&quot;)">
                                                {{ __('ui.delete') }}
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7">
                                <div class="empty-state">
                                    <svg class="empty-state__icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18.75a60.07 60.07 0 0 1 15.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 0 1 3 6h-.75m0 0v-.375c0-.621.504-1.125 1.125-1.125H20.25M2.25 6v9m18-10.5v.75c0 .414.336.75.75.75h.75m-1.5-1.5h.375c.621 0 1.125.504 1.125 1.125v9.75c0 .621-.504 1.125-1.125 1.125h-.375m1.5-1.5H21a.75.75 0 0 0-.75.75v.75m0 0H3.75m0 0h-.375a1.125 1.125 0 0 1-1.125-1.125V15m1.5 1.5v-.75A.75.75 0 0 0 3 15h-.75M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Zm3 0h.008v.008H18V10.5Zm-12 0h.008v.008H6V10.5Z"/></svg>
                                    <p class="empty-state__title">{{ __('payments.empty_title') }}</p>
                                    <p class="empty-state__desc">{{ __('payments.empty_desc') }}</p>
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
            <p class="empty-state__desc">{{ __('payments.no_match_desc') }}</p>
        </div>

        <div class="mt-6 pagination-wrap">
            {{ $payments->links() }}
        </div>
    </x-content-panel>
@endsection
