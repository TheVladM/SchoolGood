@extends('layouts.app')

@section('content')
    <section class="panel mx-auto max-w-4xl p-6">
        <div class="mb-6">
            <h1 class="text-3xl font-black text-slate-900">Creer une annee scolaire</h1>
            <p class="mt-2 text-slate-600">Definissez la periode, la date de promotion et l annee suivante a preparer.</p>
        </div>

        <form method="POST" action="{{ route('school-years.store') }}" class="space-y-6">
            @csrf
            @include('school_years._form')

            <div class="flex gap-3">
                <button type="submit" class="btn-primary">Enregistrer</button>
                <a href="{{ route('school-years.index') }}" class="btn-secondary">Annuler</a>
            </div>
        </form>
    </section>
@endsection
