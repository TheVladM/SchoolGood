@extends('layouts.app')

@section('content')
    <section class="panel mx-auto max-w-4xl p-6">
        <div class="mb-6">
            <h1 class="text-3xl font-black text-slate-900">Modifier le cours</h1>
            <p class="mt-2 text-slate-600">Mettez a jour le contenu et la planification.</p>
        </div>

        <form method="POST" action="{{ route('courses.update', $course) }}" class="space-y-6">
            @csrf
            @method('PUT')
            @include('courses._form')

            <div class="flex gap-3">
                <button type="submit" class="btn-primary">Mettre a jour</button>
                <a href="{{ route('courses.index') }}" class="btn-secondary">Retour</a>
            </div>
        </form>
    </section>
@endsection
