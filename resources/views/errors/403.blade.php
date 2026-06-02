@extends('layouts.app')

@section('title', 'Accès refusé | SchoolGood')

@section('content')
    <section class="surface-card mx-auto max-w-lg mt-12 p-8 text-center" data-reveal>
        <p class="text-6xl font-black text-slate-200">403</p>
        <h1 class="mt-4 text-2xl font-bold text-slate-900">Accès refusé</h1>
        <p class="mt-2 text-slate-600">Vous n'avez pas l'autorisation d'accéder à cette page.</p>
        <a href="{{ auth()->check() ? route('dashboard') : route('login') }}" class="btn-primary mt-6 inline-flex">
            {{ auth()->check() ? 'Retour au tableau de bord' : 'Se connecter' }}
        </a>
    </section>
@endsection
