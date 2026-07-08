@extends('layouts.app')

@section('title', __('payments.show_title') . ' | SchoolGood')
@section('topbar_title', __('nav.payments'))

@section('content')
    @include('partials.page-header', [
        'title' => $payment->student?->full_name ?? __('payments.show_title'),
        'description' => $payment->type?->label() . ' · ' . number_format((float) $payment->amount, 0, ',', ' ') . ' FCFA',
    ])

    <div class="mt-6 grid gap-6 lg:grid-cols-2" data-reveal>
        <section class="surface-card p-5 lg:p-6">
            <div class="entity-header mb-4">
                <div class="flex-1">
                    <p class="entity-header__name">{{ $payment->student?->full_name ?? __('payments.show_title') }}</p>
                    <p class="entity-header__meta">{{ $payment->type?->label() }}</p>
                </div>
                <span class="badge {{ $payment->status?->value === 'paid' ? 'badge--success' : 'badge--warning' }}">
                    {{ $payment->status?->label() }}
                </span>
            </div>
            <div class="info-list">
                <div class="info-row"><span class="info-key">{{ __('payments.info_classroom') }}</span><span class="info-val">{{ $payment->student?->classroom?->name ?: '—' }}</span></div>
                <div class="info-row"><span class="info-key">{{ __('payments.info_parent') }}</span><span class="info-val">{{ $payment->student?->parent?->name ?: '—' }}</span></div>
                <div class="info-row"><span class="info-key">{{ __('payments.info_method') }}</span><span class="info-val">{{ $payment->method?->label() ?: '—' }}</span></div>
                <div class="info-row"><span class="info-key">{{ __('payments.info_reference') }}</span><span class="info-val font-mono text-xs">{{ $payment->intent_reference ?? ($payment->reference ?: '—') }}</span></div>
                @if ($payment->receipt_number)
                    <div class="info-row"><span class="info-key">{{ __('payments.info_receipt') }}</span><span class="info-val font-mono text-xs">{{ $payment->receipt_number }}</span></div>
                @endif
                <div class="info-row"><span class="info-key">{{ __('payments.info_channel') }}</span><span class="info-val">{{ $payment->channel?->label() ?? '—' }}</span></div>
                <div class="info-row"><span class="info-key">{{ __('payments.info_received_by') }}</span><span class="info-val">{{ $payment->receivedBy?->name ?: '—' }}</span></div>
                <div class="info-row"><span class="info-key">{{ __('payments.info_validated_by') }}</span><span class="info-val">{{ $payment->validatedBy?->name ?: '—' }}</span></div>
            </div>

            <div class="mt-6 flex flex-wrap gap-3">
                <a href="{{ route('payments.index') }}" class="btn-secondary">{{ __('ui.back') }}</a>
                @if ($payment->status === \App\Enums\PaymentStatus::Paid)
                    <a href="{{ route('payments.receipt', $payment) }}" class="btn-primary">{{ __('payments.receipt_pdf') }}</a>
                @endif
                @can('update', $payment)
                    <a href="{{ route('payments.edit', $payment) }}" class="btn-primary">{{ __('ui.edit') }}</a>
                @endcan
                @can('validate', $payment)
                    @if ($payment->status !== \App\Enums\PaymentStatus::Paid)
                        <form method="POST" action="{{ route('payments.validate', $payment) }}">
                            @csrf
                            <button type="submit" class="btn-primary">{{ __('payments.validate') }}</button>
                        </form>
                    @endif
                @endcan
            </div>
        </section>

        @if ($payment->student && count($installmentBreakdown) > 0)
            <section class="surface-card p-5 lg:p-6">
                <h2 class="section-heading">{{ __('payments.tuition_balance') }}</h2>
                <p class="mt-2 text-lg font-bold text-rose-700">{{ __('payments.remaining_label') }} {{ number_format($balanceDue, 0, ',', ' ') }} FCFA</p>
                <table class="data-table mt-4">
                    <thead>
                        <tr>
                            <th>{{ __('payments.col_installment') }}</th>
                            <th>{{ __('payments.col_due') }}</th>
                            <th>{{ __('payments.col_paid') }}</th>
                            <th>{{ __('payments.col_remaining') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($installmentBreakdown as $row)
                            <tr>
                                <td>{{ $row['label'] }}</td>
                                <td>{{ number_format($row['due'], 0, ',', ' ') }}</td>
                                <td>{{ number_format($row['paid'], 0, ',', ' ') }}</td>
                                <td class="font-semibold">{{ number_format($row['remaining'], 0, ',', ' ') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </section>
        @endif
    </div>
@endsection
