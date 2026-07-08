@extends('layouts.app')

@section('title', __('ui.reset_title') . ' | SchoolGood')

@section('content')
    <div class="login-page login-page--compact">
        <section class="login-page__card mx-auto max-w-md w-full">
            <div class="login-page__card-inner">
                <x-app-logo size="md" class="login-page__card-logo" />
                <h2 class="login-page__title">{{ __('ui.reset_heading') }}</h2>

                <form method="POST" action="{{ route('password.update') }}" class="login-form">
                    @csrf
                    <input type="hidden" name="token" value="{{ $token }}">
                    <input type="hidden" name="email" value="{{ old('email', $email) }}">

                    <div class="form-field">
                        <label for="password" class="label">{{ __('ui.new_password') }}</label>
                        <input id="password" name="password" type="password" required class="field" autocomplete="new-password">
                    </div>

                    <div class="form-field">
                        <label for="password_confirmation" class="label">{{ __('ui.confirm_password') }}</label>
                        <input id="password_confirmation" name="password_confirmation" type="password" required class="field" autocomplete="new-password">
                    </div>

                    <button type="submit" class="btn-primary btn-primary--lg w-full">{{ __('ui.reset_btn') }}</button>
                </form>
            </div>
        </section>
    </div>
@endsection
