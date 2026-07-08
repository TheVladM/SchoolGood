@extends('layouts.app')

@section('title', $fee->level.' | SchoolGood')
@section('topbar_title', __('nav.tuition_fees'))

@section('content')
    @include('partials.page-header', ['title' => $fee->level.' · '.$fee->section?->label()])

    <section class="surface-card mt-6 max-w-xl p-5 lg:p-6" data-reveal>
        <div class="entity-header mb-4">
            <div class="entity-header__icon" style="background:#f0fdf4;color:#16a34a;border-radius:14px;">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" style="width:1.4rem;height:1.4rem;" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18.75a60.07 60.07 0 0 1 15.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 0 1 3 6h-.75m0 0v-.375c0-.621.504-1.125 1.125-1.125H20.25M2.25 6v9m18-10.5v.75c0 .414.336.75.75.75h.75m-1.5-1.5h.375c.621 0 1.125.504 1.125 1.125v9.75c0 .621-.504 1.125-1.125 1.125h-.375m1.5-1.5H21a.75.75 0 0 0-.75.75v.75m0 0H3.75m0 0h-.375a1.125 1.125 0 0 1-1.125-1.125V15m1.5 1.5v-.75A.75.75 0 0 0 3 15h-.75M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Zm3 0h.008v.008H18V10.5Zm-12 0h.008v.008H6V10.5Z"/></svg>
            </div>
            <div class="flex-1">
                <p class="entity-header__name">{{ $fee->level }}</p>
                <p class="entity-header__meta">{{ __('tuition_fees.section_label') }} {{ $fee->section?->label() }}</p>
            </div>
            <span class="badge {{ $fee->section?->value === 'anglophone' ? 'badge--violet' : 'badge--teal' }}">
                {{ $fee->section?->label() }}
            </span>
        </div>

        <div class="info-list">
            <div class="info-row">
                <span class="info-key">{{ __('tuition_fees.info_reg') }}</span>
                <span class="info-val font-semibold">{{ number_format((float) $fee->registration_fee, 0, ',', ' ') }} FCFA</span>
            </div>
            <div class="info-row">
                <span class="info-key">{{ __('tuition_fees.info_first') }}</span>
                <span class="info-val">{{ number_format((float) $fee->first_installment, 0, ',', ' ') }} FCFA</span>
            </div>
            <div class="info-row">
                <span class="info-key">{{ __('tuition_fees.info_second') }}</span>
                <span class="info-val">{{ number_format((float) $fee->second_installment, 0, ',', ' ') }} FCFA</span>
            </div>
            <div class="info-row">
                <span class="info-key">{{ __('tuition_fees.info_third') }}</span>
                <span class="info-val">{{ number_format((float) $fee->third_installment, 0, ',', ' ') }} FCFA</span>
            </div>
            <div class="info-row">
                <span class="info-key font-bold text-slate-900">{{ __('tuition_fees.info_total') }}</span>
                <span class="info-val font-bold text-slate-900 text-base">{{ number_format($fee->totalAnnualFees(), 0, ',', ' ') }} FCFA</span>
            </div>
        </div>

        <div class="flex gap-3 pt-6">
            <a href="{{ route('tuition-fees.index') }}" class="btn-secondary">{{ __('ui.back') }}</a>
            @can('update', $fee)
                <a href="{{ route('tuition-fees.edit', $fee) }}" class="btn-primary">{{ __('ui.edit') }}</a>
            @endcan
        </div>
    </section>
@endsection
