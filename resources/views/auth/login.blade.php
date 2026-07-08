@extends('layouts.app')

@section('title', __('ui.login_title') . ' | SchoolGood')

@section('content')
    <div class="login-page">
        <aside class="login-page__visual">
            <div class="login-page__visual-inner">
                <x-app-logo size="lg" variant="light" tagline="Gestion scolaire" class="login-page__brand-logo mb-6" />
                <h1 class="login-page__headline">{{ __('ui.login_title') }}<br>{{ __('ui.login_subtitle') }}</h1>
                <p class="login-page__text">
                    Élèves, classes, paiements, bibliothèque et messages parents — un seul espace pour toute l'équipe.
                </p>
                <ul class="login-page__list">
                    <li>Suivi pédagogique en temps réel</li>
                    <li>Espace dédié par rôle</li>
                    <li>Communication avec les familles</li>
                </ul>
            </div>
            <div class="login-page__mesh" aria-hidden="true"></div>
        </aside>

        <section class="login-page__card">
            <div class="login-page__card-inner">
                <x-app-logo size="md" class="login-page__card-logo" />
                <h2 class="login-page__title">{{ __('ui.login_title') }}</h2>
                <p class="login-page__subtitle">{{ __('ui.login_subtitle') }}</p>

                <form method="POST" action="{{ route('login.store') }}" class="login-form">
                    @csrf

                    <div class="form-field">
                        <label for="email" class="label">{{ __('ui.email_label') }}</label>
                        <input id="email" name="email" type="email" value="{{ old('email') }}" required autofocus autocomplete="email" class="field" placeholder="{{ __('ui.email_placeholder') }}">
                    </div>

                    <div class="form-field">
                        <label for="password" class="label">{{ __('ui.password_label') }}</label>
                        <div class="password-shell">
                            <input id="password" name="password" type="password" required autocomplete="current-password" class="field" placeholder="••••••••">
                            <button type="button" class="password-shell__toggle" data-password-toggle="password">{{ __('ui.view') }}</button>
                        </div>
                    </div>

                    <label class="login-form__remember">
                        <input type="checkbox" name="remember" value="1">
                        <span>{{ __('ui.remember_me') }}</span>
                    </label>

                    <button type="submit" class="btn-primary btn-primary--lg w-full">{{ __('ui.login_btn') }}</button>
                </form>

                <p class="login-page__hint">
                    <a href="{{ route('password.request') }}" class="text-indigo-600 hover:underline">{{ __('ui.forgot_password') }}</a>
                </p>

                @if (app()->environment('local'))
                    <p class="login-page__hint mt-2 text-xs text-slate-500">
                        Démo (local) : <code>founder@schoolgood.test</code> / <code>password</code>
                    </p>
                @endif
            </div>
        </section>
    </div>
@endsection
