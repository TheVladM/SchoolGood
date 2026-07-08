@extends('layouts.app')

@section('title', __('payments.pay_title') . ' | SchoolGood')
@section('topbar_title', __('nav.payments'))

@section('content')
    <x-form-shell
        :title="__('payments.pay_title')"
        :description="__('payments.pay_desc')"
        :action="route('payments.mobile.store')"
        :cancel-url="route('payments.declare')"
        :submit-label="__('payments.pay_btn')"
        max-width="max-w-2xl"
    >
        <div class="grid gap-6">
            <div>
                <label for="student_id" class="label">{{ __('payments.declare_child') }}</label>
                <select id="student_id" name="student_id" class="field" required>
                    @foreach ($children as $child)
                        <option value="{{ $child->id }}" @selected(old('student_id') == $child->id)>
                            {{ $child->full_name }} · {{ $child->classroom?->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div>
                <label for="type" class="label">{{ __('payments.form_type') }}</label>
                <select id="type" name="type" class="field" required>
                    @foreach ($types as $value => $label)
                        <option value="{{ $value }}" @selected(old('type') === $value)>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label for="amount" class="label">{{ __('payments.form_amount_fcfa') }}</label>
                <input id="amount" name="amount" type="number" min="1" step="1" class="field" value="{{ old('amount') }}" required>
            </div>
            <div>
                <label for="method" class="label">{{ __('payments.form_operator') }}</label>
                <select id="method" name="method" class="field" required>
                    @foreach ($methods as $value => $label)
                        <option value="{{ $value }}" @selected(old('method') === $value)>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label for="payer_phone" class="label">{{ __('payments.form_payer_phone') }}</label>
                <input id="payer_phone" name="payer_phone" type="tel" class="field" placeholder="6XXXXXXXX" value="{{ old('payer_phone', auth()->user()->phone) }}" required>
                <p class="mt-1 text-xs text-slate-500">{{ __('payments.form_phone_hint') }}</p>
            </div>
        </div>
    </x-form-shell>

    <p class="mt-4 text-center text-sm text-slate-500">
        {{ __('payments.pay_footer_before') }}<a href="{{ route('payments.declare') }}" class="text-indigo-600 underline">{{ __('payments.pay_footer_link') }}</a>{{ __('payments.pay_footer_after') }}
    </p>
@endsection
