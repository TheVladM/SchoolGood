@extends('layouts.app')

@section('content')
    <section class="grid gap-6 lg:grid-cols-[0.9fr_1.1fr]">
        <article class="panel p-6">
            <span class="badge">{{ $entry->day?->value }}</span>
            <h1 class="mt-4 text-3xl font-black text-slate-900">{{ $entry->subject }}</h1>
            <div class="mt-6 space-y-3 text-sm text-slate-600">
                <p><span class="font-semibold text-slate-900">Niveau:</span> {{ $entry->level }}</p>
                <p><span class="font-semibold text-slate-900">Section:</span> {{ $entry->section?->label() }}</p>
                <p><span class="font-semibold text-slate-900">Horaire:</span> {{ substr($entry->start_time, 0, 5) }} - {{ substr($entry->end_time, 0, 5) }}</p>
            </div>
        </article>

        <article class="panel p-6">
            <h2 class="text-xl font-bold text-slate-900">Notes</h2>
            <div class="mt-5 rounded-2xl bg-slate-50 p-4 text-sm leading-7 text-slate-600">
                {{ $entry->notes ?: 'Aucune note complementaire pour ce creneau.' }}
            </div>

            <div class="mt-6 flex gap-3">
                <a href="{{ route('timetable-entries.index') }}" class="btn-secondary">Retour</a>
                @if (auth()->user()->hasAnyRole([\App\Enums\UserRole::Founder, \App\Enums\UserRole::Admin, \App\Enums\UserRole::Scolarite]))
                    <a href="{{ route('timetable-entries.edit', $entry) }}" class="btn-primary">Modifier</a>
                @endif
            </div>
        </article>
    </section>
@endsection
