@extends('layouts.app')

@section('content')
    <section class="panel mx-auto max-w-4xl p-6">
        <div class="mb-6">
            <h1 class="text-3xl font-black text-slate-900">Creer un eleve</h1>
            <p class="mt-2 text-slate-600">Rattachez l'eleve a une classe et a son parent responsable.</p>
        </div>

        <form method="POST" action="{{ route('students.store') }}" class="space-y-6">
            @csrf
            @include('students._form')

            <div class="flex gap-3">
                <button type="submit" class="btn-primary">Enregistrer</button>
                <a href="{{ route('students.index') }}" class="btn-secondary">Annuler</a>
            </div>
        </form>
    </section>
@endsection
