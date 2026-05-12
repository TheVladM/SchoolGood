@extends('layouts.app')

@section('content')
    <section class="panel mx-auto max-w-4xl p-6">
        <div class="mb-6">
            <h1 class="text-3xl font-black text-slate-900">Creer une classe</h1>
            <p class="mt-2 text-slate-600">Parametrez la section, la salle et les enseignants rattaches.</p>
        </div>

        <form method="POST" action="{{ route('classrooms.store') }}" class="space-y-6">
            @csrf
            @include('classrooms._form')

            <div class="flex gap-3">
                <button type="submit" class="btn-primary">Enregistrer</button>
                <a href="{{ route('classrooms.index') }}" class="btn-secondary">Annuler</a>
            </div>
        </form>
    </section>
@endsection
