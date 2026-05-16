@extends('layouts.app')

@section('content')
    <section class="panel mx-auto max-w-4xl p-6">
        <div class="mb-6">
            <h1 class="text-3xl font-black text-slate-900">Modifier le creneau</h1>
            <p class="mt-2 text-slate-600">Ajustez les horaires communs sans recreer l emploi du temps complet.</p>
        </div>

        <form method="POST" action="{{ route('timetable-entries.update', $entry) }}" class="space-y-6">
            @csrf
            @method('PUT')
            @include('timetable_entries._form')

            <div class="flex gap-3">
                <button type="submit" class="btn-primary">Mettre a jour</button>
                <a href="{{ route('timetable-entries.index') }}" class="btn-secondary">Annuler</a>
            </div>
        </form>
    </section>
@endsection
