@extends('layouts.app')

@section('content')
    @php
        $languageLabel = match ($classroom->section?->value) {
            \App\Enums\ClassroomSection::Francophone->value => 'Enseignant d anglais',
            \App\Enums\ClassroomSection::Anglophone->value => 'Enseignant de francais',
            default => 'Enseignant de langue',
        };
    @endphp

    <section class="grid gap-6 xl:grid-cols-[0.9fr_1.1fr]">
        <article class="panel p-6">
            <span class="badge">{{ $classroom->section?->label() }}</span>
            <h1 class="mt-4 text-3xl font-black text-slate-900">{{ $classroom->name }}</h1>
            <div class="mt-6 space-y-3 text-sm text-slate-600">
                <p><span class="font-semibold text-slate-900">Niveau:</span> {{ $classroom->level }}</p>
                <p><span class="font-semibold text-slate-900">Salle:</span> {{ $classroom->room }}</p>
                <p><span class="font-semibold text-slate-900">Localisation:</span> {{ $classroom->location ?: '-' }}</p>
                <p><span class="font-semibold text-slate-900">Enseignant titulaire:</span> {{ $classroom->mainTeacher?->name ?: '-' }}</p>
                <p><span class="font-semibold text-slate-900">{{ $languageLabel }}:</span> {{ $classroom->languageTeacher?->name ?: '-' }}</p>
            </div>
        </article>

        <article class="panel p-6">
            <h2 class="text-xl font-bold text-slate-900">Eleves</h2>
            <div class="mt-5 space-y-4">
                @forelse ($classroom->students as $student)
                    <div class="rounded-2xl border border-slate-100 p-4">
                        <p class="font-semibold text-slate-900">{{ $student->full_name }}</p>
                        <p class="mt-1 text-sm text-slate-500">{{ $student->parent?->name }}</p>
                    </div>
                @empty
                    <p class="text-sm text-slate-500">Aucun eleve associe a cette classe.</p>
                @endforelse
            </div>

            <h2 class="mt-8 text-xl font-bold text-slate-900">Cours</h2>
            <div class="mt-5 space-y-4">
                @forelse ($classroom->courses as $course)
                    <div class="rounded-2xl border border-slate-100 p-4">
                        <div class="flex items-center justify-between gap-3">
                            <p class="font-semibold text-slate-900">{{ $course->title }}</p>
                            <span class="badge">{{ $course->day?->value }}</span>
                        </div>
                        <p class="mt-1 text-sm text-slate-500">{{ $course->teacher?->name }}</p>
                    </div>
                @empty
                    <p class="text-sm text-slate-500">Aucun cours programme.</p>
                @endforelse
            </div>

            <h2 class="mt-8 text-xl font-bold text-slate-900">Emploi du temps du niveau</h2>
            <div class="mt-5 space-y-4">
                @forelse ($classroom->timetableEntries as $entry)
                    <div class="rounded-2xl border border-slate-100 p-4">
                        <div class="flex items-center justify-between gap-3">
                            <p class="font-semibold text-slate-900">{{ $entry->subject }}</p>
                            <span class="badge">{{ $entry->day?->value }}</span>
                        </div>
                        <p class="mt-1 text-sm text-slate-500">{{ substr($entry->start_time, 0, 5) }} - {{ substr($entry->end_time, 0, 5) }}</p>
                        @if ($entry->notes)
                            <p class="mt-2 text-sm text-slate-500">{{ $entry->notes }}</p>
                        @endif
                    </div>
                @empty
                    <p class="text-sm text-slate-500">Aucun emploi du temps defini pour ce niveau.</p>
                @endforelse
            </div>
        </article>
    </section>
@endsection
