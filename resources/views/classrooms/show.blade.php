@extends('layouts.app')

@section('title', $classroom->name.' | SchoolGood')
@section('topbar_title', $classroom->name)

@section('content')
    @php
        $languageLabel = match ($classroom->section?->value) {
            \App\Enums\ClassroomSection::Francophone->value => 'Enseignant d’anglais',
            \App\Enums\ClassroomSection::Anglophone->value => 'Enseignant de français',
            default => 'Enseignant de langue',
        };
    @endphp

    @include('partials.page-header', [
        'title' => $classroom->name,
        'description' => $classroom->section?->label().' — Niveau '.$classroom->level,
    ])

    <div class="mt-4 flex flex-wrap gap-3" data-reveal>
        @can('update', $classroom)
            @if ($classroom->main_teacher_id)
                <form method="POST" action="{{ route('classrooms.setup-titular-courses', $classroom) }}">
                    @csrf
                    <button type="submit" class="btn-primary">
                        Titulaire = toutes les matières
                    </button>
                </form>
            @else
                <p class="text-sm text-amber-700">Assignez un enseignant titulaire pour activer le programme automatique.</p>
            @endif
            <a href="{{ route('classrooms.edit', $classroom) }}" class="btn-secondary">Modifier la classe</a>
        @endcan
    </div>

    <section class="mt-6 grid gap-6 xl:grid-cols-[0.9fr_1.1fr]" data-reveal>
        <article class="surface-card p-5 lg:p-6">
            <span class="badge">{{ $classroom->section?->label() }}</span>
            <div class="mt-6 space-y-3 text-sm text-slate-600">
                <p><span class="font-semibold text-slate-900">Niveau :</span> {{ $classroom->level }}</p>
                <p><span class="font-semibold text-slate-900">Salle :</span> {{ $classroom->room }}</p>
                <p><span class="font-semibold text-slate-900">Localisation :</span> {{ $classroom->location ?: '—' }}</p>
                <p><span class="font-semibold text-slate-900">Titulaire :</span> {{ $classroom->mainTeacher?->name ?: '—' }}</p>
                <p><span class="font-semibold text-slate-900">{{ $languageLabel }} :</span> {{ $classroom->languageTeacher?->name ?: '—' }}</p>
            </div>
        </article>

        <article class="surface-card p-5 lg:p-6">
            <h2 class="text-xl font-bold text-slate-900">Élèves</h2>
            <div class="mt-5 space-y-4">
                @forelse ($classroom->students as $student)
                    <div class="rounded-2xl border border-slate-100 p-4">
                        <p class="font-semibold text-slate-900">{{ $student->full_name }}</p>
                        <p class="mt-1 text-sm text-slate-500">{{ $student->parent?->name }}</p>
                    </div>
                @empty
                    <p class="text-sm text-slate-500">Aucun élève dans cette classe.</p>
                @endforelse
            </div>

            <h2 class="mt-8 text-xl font-bold text-slate-900">Cours</h2>
            <div class="mt-5 space-y-4">
                @forelse ($classroom->courses as $course)
                    <div class="rounded-2xl border border-slate-100 p-4">
                        <div class="flex items-center justify-between gap-3">
                            <a href="{{ route('courses.show', $course) }}" class="font-semibold text-slate-900 hover:text-indigo-600">
                                {{ $course->title }}
                            </a>
                            <span class="badge">{{ $course->day?->value }}</span>
                        </div>
                        <p class="mt-1 text-sm text-slate-500">{{ $course->teacher?->name }}</p>
                    </div>
                @empty
                    <p class="text-sm text-slate-500">Aucun cours. Utilisez le bouton « Titulaire = toutes les matières » ou l’emploi du temps.</p>
                @endforelse
            </div>

            <h2 class="mt-8 text-xl font-bold text-slate-900">Emploi du temps (niveau)</h2>
            <div class="mt-5 space-y-4">
                @forelse ($classroom->timetableEntries as $entry)
                    <div class="rounded-2xl border border-slate-100 p-4">
                        <div class="flex items-center justify-between gap-3">
                            <p class="font-semibold text-slate-900">{{ $entry->subject }}</p>
                            <span class="badge">{{ $entry->day?->value }}</span>
                        </div>
                        <p class="mt-1 text-sm text-slate-500">{{ substr($entry->start_time, 0, 5) }} – {{ substr($entry->end_time, 0, 5) }}</p>
                        @if ($entry->notes)
                            <p class="mt-2 text-sm text-slate-500">{{ $entry->notes }}</p>
                        @endif
                    </div>
                @empty
                    <p class="text-sm text-slate-500">Aucun créneau pour ce niveau.</p>
                @endforelse
            </div>

            <div class="mt-6">
                <a href="{{ route('classrooms.index') }}" class="btn-secondary">Retour</a>
            </div>
        </article>
    </section>
@endsection
