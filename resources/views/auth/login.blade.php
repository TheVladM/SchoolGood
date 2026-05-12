@extends('layouts.app')

@section('title', 'Connexion | schoolGood')

@section('content')
    <div class="auth-shell py-4 lg:py-8">
        <div class="split-layout">
            <section class="auth-hero" data-reveal>
                <span class="page-hero__eyebrow">Plateforme scolaire bilingue</span>
                <h1 class="page-hero__title max-w-3xl">
                    Une interface nette pour piloter l'administration, les classes et la relation parentale.
                </h1>
                <p class="page-hero__description">
                    schoolGood rassemble les operations academiques et financieres dans un espace clair,
                    rapide et adapte a chaque role.
                </p>

                <div class="quick-grid mt-6">
                    <div class="quick-card">
                        <p class="quick-card__title">5 roles metier</p>
                        <p class="quick-card__text">Fondateur, admin, scolarite, enseignant et parent.</p>
                    </div>
                    <div class="quick-card">
                        <p class="quick-card__title">Gestion en temps reel</p>
                        <p class="quick-card__text">Classes, eleves, cours et paiements centralises.</p>
                    </div>
                    <div class="quick-card">
                        <p class="quick-card__title">Vue par profil</p>
                        <p class="quick-card__text">Chaque utilisateur voit les actions qui comptent pour lui.</p>
                    </div>
                </div>

                <div class="auth-role-grid">
                    <article class="auth-role-card">
                        <p class="auth-role-card__title">Administration</p>
                        <p class="auth-role-card__text">Suivi des effectifs, affectations et encaissements.</p>
                    </article>
                    <article class="auth-role-card">
                        <p class="auth-role-card__title">Pedagogie</p>
                        <p class="auth-role-card__text">Organisation des classes, emploi du temps et contenus de cours.</p>
                    </article>
                    <article class="auth-role-card">
                        <p class="auth-role-card__title">Parents</p>
                        <p class="auth-role-card__text">Lecture simple des inscriptions, classes et paiements.</p>
                    </article>
                    <article class="auth-role-card">
                        <p class="auth-role-card__title">Experience mobile</p>
                        <p class="auth-role-card__text">Navigation fluide, cartes lisibles et formulaires plus propres.</p>
                    </article>
                </div>
            </section>

            <section class="surface-card p-6 lg:p-8" data-reveal>
                <div class="flex flex-wrap items-start justify-between gap-4">
                    <div>
                        <span class="badge bg-amber-100 text-amber-700">Connexion securisee</span>
                        <h2 class="mt-4 text-4xl font-black text-slate-900">Acceder a votre espace</h2>
                        <p class="mt-3 max-w-lg text-base leading-7 text-slate-600">
                            Connectez-vous pour retrouver votre dashboard personnalise et vos modules de travail.
                        </p>
                    </div>

                    <div class="helper-card max-w-xs">
                        <p class="helper-card__title">Compte demo</p>
                        <p class="helper-card__text">
                            <span class="font-mono text-slate-900">founder@schoolgood.test</span><br>
                            <span class="font-mono text-slate-900">password</span>
                        </p>
                    </div>
                </div>

                <form method="POST" action="{{ route('login.store') }}" class="mt-8 space-y-5">
                    @csrf

                    <div>
                        <label for="email" class="label">Adresse email</label>
                        <input
                            id="email"
                            name="email"
                            type="email"
                            value="{{ old('email') }}"
                            required
                            autofocus
                            autocomplete="email"
                            class="field"
                            placeholder="vous@schoolgood.test"
                        >
                    </div>

                    <div>
                        <label for="password" class="label">Mot de passe</label>
                        <div class="password-shell">
                            <input
                                id="password"
                                name="password"
                                type="password"
                                required
                                autocomplete="current-password"
                                class="field pr-24"
                                placeholder="Mot de passe"
                            >
                            <button type="button" class="password-shell__toggle" data-password-toggle="password">
                                Afficher
                            </button>
                        </div>
                    </div>

                    <div class="flex flex-wrap items-center justify-between gap-4">
                        <label class="flex items-center gap-3 text-sm text-slate-600">
                            <input type="checkbox" name="remember" value="1" class="h-4 w-4 rounded border-slate-300 text-amber-500">
                            Se souvenir de moi
                        </label>

                        <span class="text-sm font-medium text-slate-500">Acces role-based inclus</span>
                    </div>

                    <button type="submit" class="btn-primary w-full">Entrer dans schoolGood</button>
                </form>
            </section>
        </div>
    </div>
@endsection
