@extends('layouts.app')

@section('content')
    <section class="grid gap-6 lg:grid-cols-[0.9fr_1.1fr]">
        <article class="panel p-6">
            <span class="badge">{{ $course->day?->value }}</span>
            <h1 class="mt-4 text-3xl font-black text-slate-900">{{ $course->title }}</h1>
            <div class="mt-6 space-y-3 text-sm text-slate-600">
                <p><span class="font-semibold text-slate-900">Classe:</span> {{ $course->classroom?->name }}</p>
                <p><span class="font-semibold text-slate-900">Enseignant:</span> {{ $course->teacher?->name }}</p>
            </div>
        </article>

        <article class="panel p-6">
            <h2 class="text-xl font-bold text-slate-900">Contenu pedagogique</h2>
            <p class="mt-4 whitespace-pre-line text-slate-600">{{ $course->content }}</p>

            <div class="mt-6 flex gap-3">
                <a href="{{ route('courses.index') }}" class="btn-secondary">Retour</a>
                @if (
                    auth()->user()->hasAnyRole([\App\Enums\UserRole::Founder, \App\Enums\UserRole::Admin, \App\Enums\UserRole::Scolarite]) ||
                    (auth()->user()->hasRole(\App\Enums\UserRole::Teacher) && $course->teacher_id === auth()->id())
                )
                    <a href="{{ route('courses.edit', $course) }}" class="btn-primary">Modifier</a>
                @endif
            </div>
        </article>
    </section>
@endsection
