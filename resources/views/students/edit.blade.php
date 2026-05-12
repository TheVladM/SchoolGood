@extends('layouts.app')

@section('content')
    <section class="panel mx-auto max-w-4xl p-6">
        <div class="mb-6">
            <h1 class="text-3xl font-black text-slate-900">Modifier l'eleve</h1>
            <p class="mt-2 text-slate-600">Mettez a jour les informations scolaires et parentales.</p>
        </div>

        <form method="POST" action="{{ route('students.update', $student) }}" class="space-y-6">
            @csrf
            @method('PUT')
            @include('students._form')

            <div class="flex gap-3">
                <button type="submit" class="btn-primary">Mettre a jour</button>
                <a href="{{ route('students.index') }}" class="btn-secondary">Retour</a>
            </div>
        </form>
    </section>
@endsection
