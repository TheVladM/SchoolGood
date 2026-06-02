@extends('layouts.app')

@section('title', $entry->subject.' | SchoolGood')
@section('topbar_title', 'Emploi du temps')

@section('content')
    <x-show-shell
        :title="$entry->subject"
        :description="$entry->level.' — '.$entry->section?->label()"
        :back-url="route('timetable-entries.index')"
    >
        <section class="grid gap-6 lg:grid-cols-[0.9fr_1.1fr]">
            <article class="surface-card p-5 lg:p-6">
                <span class="badge">{{ $entry->day?->value }}</span>
                <div class="mt-6 space-y-3 text-sm text-slate-600">
                    <p><span class="font-semibold text-slate-900">Horaire :</span> {{ substr($entry->start_time, 0, 5) }} – {{ substr($entry->end_time, 0, 5) }}</p>
                </div>
            </article>

            <article class="surface-card p-5 lg:p-6">
                <h2 class="text-xl font-bold text-slate-900">Notes</h2>
                <p class="mt-4 text-sm leading-7 text-slate-600">
                    {{ $entry->notes ?: 'Aucune note pour ce créneau.' }}
                </p>
                @if (auth()->user()->hasAnyRole([\App\Enums\UserRole::Founder, \App\Enums\UserRole::Admin, \App\Enums\UserRole::Scolarite]))
                    <a href="{{ route('timetable-entries.edit', $entry) }}" class="btn-primary mt-6 inline-flex">Modifier</a>
                @endif
            </article>
        </section>
    </x-show-shell>
@endsection
