@extends('layouts.app')

@section('content')
    <section class="panel mx-auto max-w-4xl p-6">
        <div class="mb-6">
            <h1 class="text-3xl font-black text-slate-900">Creer un cours</h1>
            <p class="mt-2 text-slate-600">Affectez le cours a une classe, un enseignant et un jour.</p>
        </div>

        <form method="POST" action="{{ route('courses.store') }}" class="space-y-6">
            @csrf
            @include('courses._form')

            <div class="flex gap-3">
                <button type="submit" class="btn-primary">Enregistrer</button>
                <a href="{{ route('courses.index') }}" class="btn-secondary">Annuler</a>
            </div>
        </form>
    </section>
@endsection
