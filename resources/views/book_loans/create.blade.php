@extends('layouts.app')

@section('content')
    <section class="panel mx-auto max-w-4xl p-6">
        <div class="mb-6">
            <h1 class="text-3xl font-black text-slate-900">Enregistrer un emprunt</h1>
            <p class="mt-2 text-slate-600">Associez le livre a un eleve ou a un enseignant, avec une date limite de retour.</p>
        </div>

        <form method="POST" action="{{ route('book-loans.store') }}" class="space-y-6">
            @csrf
            @include('book_loans._form')

            <div class="flex gap-3">
                <button type="submit" class="btn-primary">Enregistrer</button>
                <a href="{{ route('book-loans.index') }}" class="btn-secondary">Annuler</a>
            </div>
        </form>
    </section>
@endsection
