@extends('layouts.app')

@section('title', 'Mot de passe oublié | SchoolGood')

@section('content')
    <div class="login-page login-page--compact">
        <section class="login-page__card mx-auto max-w-md w-full">
            <div class="login-page__card-inner">
                <x-app-logo size="md" class="login-page__card-logo" />
                <h2 class="login-page__title">Mot de passe oublié</h2>
                <p class="login-page__subtitle">Indiquez votre email pour recevoir un lien de réinitialisation.</p>

                <form method="POST" action="{{ route('password.email') }}" class="login-form">
                    @csrf
                    <div class="form-field">
                        <label for="email" class="label">Adresse email</label>
                        <input id="email" name="email" type="email" value="{{ old('email') }}" required class="field" autocomplete="email">
                    </div>
                    <button type="submit" class="btn-primary btn-primary--lg w-full">Envoyer le lien</button>
                </form>

                <p class="login-page__hint mt-4">
                    <a href="{{ route('login') }}" class="text-indigo-600 hover:underline">Retour à la connexion</a>
                </p>
            </div>
        </section>
    </div>
@endsection
