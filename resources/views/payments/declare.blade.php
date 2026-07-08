@extends('layouts.app')

@section('title', __('payments.declare_title') . ' | SchoolGood')
@section('topbar_title', __('nav.payments'))

@section('content')
    @include('partials.page-header', [
        'title'       => __('payments.declare_title'),
        'description' => __('payments.declare_desc'),
    ])

    <div class="mt-6 space-y-5 max-w-2xl">

        {{-- Coordonnées bancaires --}}
        <section class="surface-card p-5 lg:p-6" data-reveal>
            <h2 class="section-heading">{{ __('payments.school_accounts') }}</h2>
            <ul class="space-y-3">
                @foreach ($accounts as $key => $account)
                    <li class="flex items-start gap-3 rounded-xl border border-slate-200 bg-slate-50 p-3">
                        <div class="avatar avatar--slate" style="width:2.25rem;height:2.25rem;font-size:0.65rem;flex-shrink:0;">
                            {{ mb_strtoupper(mb_substr($account['label'] ?? $key, 0, 2)) }}
                        </div>
                        <div>
                            <p class="font-semibold text-slate-900 text-sm">{{ $account['label'] ?? $key }}</p>
                            @if (!empty($account['number']))
                                <p class="text-xs text-slate-600 mt-0.5 font-mono">Numéro : {{ $account['number'] }}</p>
                            @endif
                            @if (!empty($account['account']))
                                <p class="text-xs text-slate-600 font-mono">
                                    Compte : {{ $account['account'] }}
                                    @if (!empty($account['bank_name'])) ({{ $account['bank_name'] }}) @endif
                                </p>
                            @endif
                        </div>
                    </li>
                @endforeach
            </ul>
        </section>

        {{-- Paiement en ligne --}}
        <section class="surface-card p-5 lg:p-6" data-reveal>
            <div class="flex items-start gap-4">
                <div class="entity-header__icon" style="background:#ede9fe;color:#7c3aed;border-radius:14px;flex-shrink:0;">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" style="width:1.4rem;height:1.4rem;" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 8.25h19.5M2.25 9h19.5m-16.5 5.25h6m-6 2.25h3m-3.75 3h15a2.25 2.25 0 0 0 2.25-2.25V6.75A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25v10.5A2.25 2.25 0 0 0 4.5 19.5Z"/>
                    </svg>
                </div>
                <div class="flex-1">
                    <div class="flex items-center gap-2">
                        <h2 class="text-base font-semibold text-slate-900">{{ __('payments.online_payment') }}</h2>
                        <span class="badge badge--violet">{{ __('payments.online_badge') }}</span>
                    </div>
                    <p class="text-sm text-slate-600 mt-1">{{ __('payments.online_desc') }}</p>
                    <a href="{{ route('payments.mobile.create') }}" class="btn-primary mt-4 inline-flex">
                        <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 1.5H8.25A2.25 2.25 0 0 0 6 3.75v16.5a2.25 2.25 0 0 0 2.25 2.25h7.5A2.25 2.25 0 0 0 18 20.25V3.75a2.25 2.25 0 0 0-2.25-2.25H13.5m-3 0V3h3V1.5m-3 0h3"/>
                        </svg>
                        {{ __('payments.pay_online_btn') }}
                    </a>
                </div>
            </div>
        </section>

        {{-- Déclaration manuelle --}}
        <section class="surface-card p-5 lg:p-6" data-reveal>
            <h2 class="section-heading">{{ __('payments.manual_declare') }}</h2>
            <p class="text-sm text-slate-600 mb-5">{{ __('payments.manual_desc') }}</p>

            <form method="POST" action="{{ route('payments.declare.store') }}" class="grid gap-5 md:grid-cols-2">
                @csrf

                <div class="md:col-span-2">
                    <label class="label" for="student_id">{{ __('payments.declare_child') }}</label>
                    <select id="student_id" name="student_id" class="field" required>
                        @foreach ($children as $child)
                            <option value="{{ $child->id }}">{{ $child->full_name }} · {{ $child->classroom?->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="label" for="type">{{ __('payments.form_type') }}</label>
                    <select id="type" name="type" class="field" required>
                        @foreach ($types as $value => $label)
                            <option value="{{ $value }}">{{ $label }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="label" for="amount">{{ __('payments.form_amount_fcfa') }}</label>
                    <input id="amount" name="amount" type="number" min="0" step="1" class="field" required>
                </div>

                <div>
                    <label class="label" for="method">{{ __('payments.form_method') }}</label>
                    <select id="method" name="method" class="field" required>
                        @foreach ($methods as $value => $label)
                            <option value="{{ $value }}">{{ $label }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="label" for="reference">{{ __('payments.form_ref_tx') }}</label>
                    <input id="reference" name="reference" class="field">
                </div>

                <div class="md:col-span-2">
                    <label class="label" for="account_reference">{{ __('payments.form_account_used') }}</label>
                    <input id="account_reference" name="account_reference" class="field">
                </div>

                <div class="md:col-span-2">
                    <button type="submit" class="btn-primary">{{ __('payments.declare_send') }}</button>
                </div>
            </form>
        </section>

    </div>
@endsection
