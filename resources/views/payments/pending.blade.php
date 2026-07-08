@extends('layouts.app')

@section('title', __('payments.pending_title') . ' | SchoolGood')
@section('topbar_title', __('nav.payments'))

@section('content')
    @include('partials.page-header', [
        'title' => __('payments.pending_title'),
        'description' => __('payments.info_reference') . ' ' . $payment->intent_reference,
    ])

    <section class="surface-card mt-6 max-w-xl p-5 lg:p-6" data-reveal>
        <div class="info-list">
            <div class="info-row"><span class="info-key">{{ __('payments.col_student') }}</span><span class="info-val">{{ $payment->student?->full_name }}</span></div>
            <div class="info-row"><span class="info-key">{{ __('payments.col_amount') }}</span><span class="info-val font-semibold">{{ number_format((float) $payment->amount, 0, ',', ' ') }} FCFA</span></div>
            <div class="info-row"><span class="info-key">{{ __('payments.operator_label') }}</span><span class="info-val">{{ $payment->method?->label() }}</span></div>
            <div class="info-row"><span class="info-key">{{ __('payments.status_operator') }}</span><span class="info-val">{{ $payment->status?->label() }} / {{ $payment->operator_status ?: '—' }}</span></div>
        </div>

        <p class="mt-6 text-sm text-slate-600">
            {{ str_replace(':operator', $payment->method?->label(), __('payments.pending_note')) }}
        </p>

        <div class="mt-6 flex flex-wrap gap-3">
            <a href="{{ route('payments.index') }}" class="btn-secondary">{{ __('payments.my_payments') }}</a>
            @if ($payment->status === \App\Enums\PaymentStatus::Paid)
                <a href="{{ route('payments.receipt', $payment) }}" class="btn-primary">{{ __('payments.download_receipt') }}</a>
            @endif
        </div>
    </section>
@endsection
