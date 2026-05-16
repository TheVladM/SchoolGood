@extends('layouts.app')

@section('content')
    <section class="panel mx-auto max-w-4xl p-6">
        <div class="mb-6">
            <h1 class="text-3xl font-black text-slate-900">Creer un emploi du temps</h1>
            <p class="mt-2 text-slate-600">Ce creneau sera partage par toutes les classes du meme niveau et de la meme section.</p>
        </div>

        <form method="POST" action="{{ route('timetable-entries.store') }}" class="space-y-6">
            @csrf
            @include('timetable_entries._form')

            <div class="flex gap-3">
                <button type="submit" class="btn-primary">Enregistrer</button>
                <a href="{{ route('timetable-entries.index') }}" class="btn-secondary">Annuler</a>
            </div>
        </form>
    </section>
@endsection
