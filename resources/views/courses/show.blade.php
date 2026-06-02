@extends('layouts.app')

@section('title', $course->title.' | SchoolGood')
@section('topbar_title', 'Cours')

@section('content')
    <x-show-shell
        :title="$course->title"
        :description="$course->classroom?->name.' — '.$course->teacher?->name"
        :back-url="route('courses.index')"
    >
        <section class="grid gap-6 lg:grid-cols-[0.9fr_1.1fr]">
            <article class="surface-card p-5 lg:p-6">
                <span class="badge">{{ $course->day?->value }}</span>
                <div class="mt-6 space-y-3 text-sm text-slate-600">
                    <p><span class="font-semibold text-slate-900">Classe :</span> {{ $course->classroom?->name }}</p>
                    <p><span class="font-semibold text-slate-900">Enseignant :</span> {{ $course->teacher?->name }}</p>
                    @if ($course->timetable_entry_id)
                        <p><span class="font-semibold text-slate-900">Source :</span> Emploi du temps (synchronisé)</p>
                    @endif
                </div>
            </article>

            <article class="surface-card p-5 lg:p-6">
                <h2 class="text-xl font-bold text-slate-900">Contenu pédagogique</h2>
                <p class="mt-4 whitespace-pre-line text-slate-600">{{ $course->content }}</p>

                @if (
                    auth()->user()->hasAnyRole([\App\Enums\UserRole::Founder, \App\Enums\UserRole::Admin, \App\Enums\UserRole::Scolarite]) ||
                    (auth()->user()->hasRole(\App\Enums\UserRole::Teacher) && $course->teacher_id === auth()->id())
                )
                    <a href="{{ route('courses.edit', $course) }}" class="btn-primary mt-6 inline-flex">Modifier</a>
                @endif
            </article>
        </section>
    </x-show-shell>
@endsection
