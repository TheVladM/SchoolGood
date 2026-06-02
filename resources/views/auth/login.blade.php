@extends('layouts.app')

@section('title', 'Connexion | SchoolGood')

@section('content')
    <div class="login-page">
        <aside class="login-page__visual">
            <div class="login-page__visual-inner">
                <x-app-logo size="lg" variant="light" tagline="Gestion scolaire" class="login-page__brand-logo mb-6" />
                <h1 class="login-page__headline">La gestion scolaire,<br>simplifiée.</h1>
                <p class="login-page__text">
                    Eleves, classes, paiements, bibliotheque et messages parents, un seul espace pour toute l equipe.
                </p>
                <ul class="login-page__list">
                    <li>Suivi pedagogique en temps reel</li>
                    <li>Espace dedie par role</li>
                    <li>Communication avec les familles</li>
                </ul>
            </div>
            <div class="login-page__mesh" aria-hidden="true"></div>
        </aside>

        <section class="login-page__card">
            <div class="login-page__card-inner">
                <x-app-logo size="md" class="login-page__card-logo" />
                <h2 class="login-page__title">Connexion</h2>
                <p class="login-page__subtitle">Entrez vos identifiants pour continuer.</p>

                <form method="POST" action="{{ route('login.store') }}" class="login-form">
                    @csrf

                    <div class="form-field">
                        <label for="email" class="label">Adresse email</label>
                        <input id="email" name="email" type="email" value="{{ old('email') }}" required autofocus autocomplete="email" class="field" placeholder="nom@exemple.com">
                    </div>

                    <div class="form-field">
                        <label for="password" class="label">Mot de passe</label>
                        <div class="password-shell">
                            <input id="password" name="password" type="password" required autocomplete="current-password" class="field" placeholder="••••••••">
                            <button type="button" class="password-shell__toggle" data-password-toggle="password">Voir</button>
                        </div>
                    </div>

                    <label class="login-form__remember">
                        <input type="checkbox" name="remember" value="1">
                        <span>Se souvenir de moi</span>
                    </label>

                    <button type="submit" class="btn-primary btn-primary--lg w-full">Se connecter</button>
                </form>

                <p class="login-page__hint">
                    <a href="{{ route('password.request') }}" class="text-indigo-600 hover:underline">Mot de passe oublié ?</a>
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
