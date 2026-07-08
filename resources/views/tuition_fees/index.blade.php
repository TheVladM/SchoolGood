@extends('layouts.app')

@section('title', __('tuition_fees.page_title') . ' | SchoolGood')
@section('topbar_title', __('nav.tuition_fees'))

@section('content')
    @include('partials.page-header', [
        'title' => __('tuition_fees.page_title'),
        'description' => __('tuition_fees.page_desc'),
        'statValue' => $fees->total(),
        'statLabel' => __('tuition_fees.stat_label'),
    ])

    <x-content-panel class="mt-6" :title="__('tuition_fees.grid')" :subtitle="__('tuition_fees.grid_subtitle')">
        <x-slot:toolbar>
            <div class="content-panel__toolbar">
                @can('create', \App\Models\TuitionFee::class)
                    <a href="{{ route('tuition-fees.create') }}" class="btn-primary self-end">
                        <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
                        {{ __('tuition_fees.new_grid') }}
                    </a>
                @endcan
            </div>
        </x-slot:toolbar>

        <div class="overflow-x-auto table-shell">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>{{ __('tuition_fees.col_level') }}</th>
                        <th>{{ __('tuition_fees.col_section') }}</th>
                        <th>{{ __('tuition_fees.col_reg_fee') }}</th>
                        <th>{{ __('tuition_fees.col_total') }}</th>
                        <th class="text-right">{{ __('ui.actions_col') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($fees as $fee)
                        <tr>
                            <td class="font-semibold text-slate-900">{{ $fee->level }}</td>
                            <td>
                                <span class="badge {{ $fee->section?->value === 'anglophone' ? 'badge--violet' : 'badge--teal' }}">
                                    {{ $fee->section?->label() }}
                                </span>
                            </td>
                            <td class="text-slate-700">{{ number_format((float) $fee->registration_fee, 0, ',', ' ') }} FCFA</td>
                            <td class="font-semibold text-slate-900">{{ number_format($fee->totalAnnualFees(), 0, ',', ' ') }} FCFA</td>
                            <td>
                                <div class="record-actions justify-end">
                                    <a href="{{ route('tuition-fees.show', $fee) }}" class="btn-secondary">{{ __('ui.view') }}</a>
                                    @can('update', $fee)
                                        <a href="{{ route('tuition-fees.edit', $fee) }}" class="btn-secondary">{{ __('ui.edit') }}</a>
                                    @endcan
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5">
                                <div class="empty-state">
                                    <svg class="empty-state__icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18.75a60.07 60.07 0 0 1 15.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 0 1 3 6h-.75m0 0v-.375c0-.621.504-1.125 1.125-1.125H20.25M2.25 6v9m18-10.5v.75c0 .414.336.75.75.75h.75m-1.5-1.5h.375c.621 0 1.125.504 1.125 1.125v9.75c0 .621-.504 1.125-1.125 1.125h-.375m1.5-1.5H21a.75.75 0 0 0-.75.75v.75m0 0H3.75m0 0h-.375a1.125 1.125 0 0 1-1.125-1.125V15m1.5 1.5v-.75A.75.75 0 0 0 3 15h-.75M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Zm3 0h.008v.008H18V10.5Zm-12 0h.008v.008H6V10.5Z"/></svg>
                                    <p class="empty-state__title">{{ __('tuition_fees.empty_title') }}</p>
                                    <p class="empty-state__desc">{{ __('tuition_fees.empty_desc') }}</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4 pagination-wrap">{{ $fees->links() }}</div>
    </x-content-panel>
@endsection
