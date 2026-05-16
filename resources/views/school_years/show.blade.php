@extends('layouts.app')

@section('content')
    <section class="grid gap-6 xl:grid-cols-[0.9fr_1.1fr]">
        <article class="panel p-6">
            <span class="badge">{{ $schoolYear->status?->label() }}</span>
            <h1 class="mt-4 text-3xl font-black text-slate-900">{{ $schoolYear->name }}</h1>
            <div class="mt-6 space-y-3 text-sm text-slate-600">
                <p><span class="font-semibold text-slate-900">Periode:</span> {{ $schoolYear->starts_on?->format('d/m/Y') }} - {{ $schoolYear->ends_on?->format('d/m/Y') }}</p>
                <p><span class="font-semibold text-slate-900">Remise des diplomes:</span> {{ $schoolYear->diploma_awarded_on?->format('d/m/Y') ?: '-' }}</p>
                <p><span class="font-semibold text-slate-900">Preparation des promotions:</span> {{ $schoolYear->promotion_opens_on?->format('d/m/Y') ?: '-' }}</p>
                <p><span class="font-semibold text-slate-900">Annee suivante:</span> {{ $schoolYear->nextSchoolYear?->name ?: '-' }}</p>
                <p><span class="font-semibold text-slate-900">Promotions lancees le:</span> {{ $schoolYear->promoted_at?->format('d/m/Y H:i') ?: '-' }}</p>
            </div>

            <div class="mt-6 flex flex-wrap gap-3">
                <a href="{{ route('school-years.index') }}" class="btn-secondary">Retour</a>
                <a href="{{ route('school-years.edit', $schoolYear) }}" class="btn-primary">Modifier</a>

                @if ($schoolYear->canPreparePromotions() && $schoolYear->nextSchoolYear)
                    <form method="POST" action="{{ route('school-years.prepare-promotions', $schoolYear) }}">
                        @csrf
                        <button type="submit" class="btn-primary" onclick="return confirm('Preparer les promotions vers l annee suivante ?')">
                            Preparer les promotions
                        </button>
                    </form>
                @endif
            </div>
        </article>

        <article class="panel p-6">
            <h2 class="text-xl font-bold text-slate-900">Dossiers annuels des eleves</h2>
            <div class="mt-5 space-y-4">
                @forelse ($studentRecords as $record)
                    <div class="rounded-2xl border border-slate-100 p-4">
                        <div class="flex items-center justify-between gap-3">
                            <p class="font-semibold text-slate-900">{{ $record->student?->full_name }}</p>
                            <span class="badge">{{ $record->status?->label() }}</span>
                        </div>
                        <p class="mt-1 text-sm text-slate-500">{{ $record->classroom_name_snapshot }} / {{ $record->level_snapshot }}</p>
                        <p class="mt-1 text-sm text-slate-500">{{ $record->student?->parent?->name }}</p>
                    </div>
                @empty
                    <p class="text-sm text-slate-500">Aucun dossier eleve pour cette annee scolaire.</p>
                @endforelse
            </div>
        </article>
    </section>
@endsection
