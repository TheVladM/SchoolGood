@extends('layouts.app')

@section('title', 'Mon profil | SchoolGood')
@section('topbar_title', 'Mon profil')

@section('content')
    @include('partials.page-header', [
        'title' => 'Mon profil',
        'description' => 'Coordonnées et mot de passe.',
    ])

    <section class="surface-card mt-6 mx-auto max-w-2xl p-5 lg:p-6" data-reveal>
        <form method="POST" action="{{ route('profile.update') }}" class="space-y-5">
            @csrf
            @method('PUT')

            <div class="form-field">
                <label for="name" class="label">Nom complet</label>
                <input id="name" name="name" type="text" required class="field" value="{{ old('name', $user->name) }}">
                @error('name')<p class="form-field__error">{{ $message }}</p>@enderror
            </div>

            <div class="form-field">
                <label for="email" class="label">Adresse email</label>
                <input id="email" name="email" type="email" required class="field" value="{{ old('email', $user->email) }}">
                @error('email')<p class="form-field__error">{{ $message }}</p>@enderror
            </div>

            <div class="form-field">
                <label for="phone" class="label">Téléphone</label>
                <input id="phone" name="phone" type="tel" class="field" value="{{ old('phone', $user->phone) }}">
                @error('phone')<p class="form-field__error">{{ $message }}</p>@enderror
            </div>

            <hr class="border-slate-200">

            <p class="text-sm text-slate-600">Laissez vide pour conserver le mot de passe actuel.</p>

            <div class="form-field">
                <label for="current_password" class="label">Mot de passe actuel</label>
                <input id="current_password" name="current_password" type="password" class="field" autocomplete="current-password">
                @error('current_password')<p class="form-field__error">{{ $message }}</p>@enderror
            </div>

            <div class="form-field">
                <label for="password" class="label">Nouveau mot de passe</label>
                <input id="password" name="password" type="password" class="field" autocomplete="new-password">
                @error('password')<p class="form-field__error">{{ $message }}</p>@enderror
            </div>

            <div class="form-field">
                <label for="password_confirmation" class="label">Confirmer le mot de passe</label>
                <input id="password_confirmation" name="password_confirmation" type="password" class="field" autocomplete="new-password">
            </div>

            <div class="flex gap-3 pt-2">
                <button type="submit" class="btn-primary">Enregistrer</button>
                <a href="{{ route('dashboard') }}" class="btn-secondary">Annuler</a>
            </div>
        </form>
    </section>
@endsection
