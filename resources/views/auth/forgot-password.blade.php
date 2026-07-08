@extends('layouts.app')

@section('title', __('ui.forgot_title') . ' | SchoolGood')

@section('content')
    <div class="login-page login-page--compact">
        <section class="login-page__card mx-auto max-w-md w-full">
            <div class="login-page__card-inner">
                <x-app-logo size="md" class="login-page__card-logo" />
                <h2 class="login-page__title">{{ __('ui.forgot_title') }}</h2>
                <p class="login-page__subtitle">{{ __('ui.forgot_desc') }}</p>

                <form method="POST" action="{{ route('password.email') }}" class="login-form">
                    @csrf
                    <div class="form-field">
                        <label for="email" class="label">{{ __('ui.email_label') }}</label>
                        <input id="email" name="email" type="email" value="{{ old('email') }}" required class="field" autocomplete="email">
                    </div>
                    <button type="submit" class="btn-primary btn-primary--lg w-full">{{ __('ui.forgot_btn') }}</button>
                </form>

                <p class="login-page__hint mt-4">
                    <a href="{{ route('login') }}" class="text-indigo-600 hover:underline">{{ __('ui.back_to_login') }}</a>
                </p>
            </div>
        </section>
    </div>
@endsection
