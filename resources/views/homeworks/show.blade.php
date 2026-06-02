@extends('layouts.app')

@section('title', $homework->title . ' | SchoolGood')
@section('topbar_title', 'Détail du devoir')

@section('content')
    @include('partials.page-header', [
        'title' => $homework->title,
        'description' => ($homework->subject ?? 'Devoir') . ' — ' . $homework->classroom->name . ' — échéance ' . $homework->due_date->format('d/m/Y'),
    ])

    <div class="mt-6 grid gap-6 lg:grid-cols-3" data-reveal>
        <div class="lg:col-span-2 space-y-6">
            <x-content-panel title="Consignes" subtitle="Description et pièces jointes">
                @if ($homework->description)
                    <div class="prose prose-slate max-w-none whitespace-pre-wrap text-slate-700">{{ $homework->description }}</div>
                @else
                    <p class="text-slate-500">Aucune description.</p>
                @endif

                <div class="mt-4 flex flex-wrap gap-3">
                    @can('update', $homework)
                        <a href="{{ route('homeworks.edit', $homework) }}" class="btn-primary">Modifier</a>
                    @endcan
                    <a href="{{ route('homeworks.index') }}" class="btn-secondary">Retour</a>
                </div>
            </x-content-panel>

            <x-content-panel title="Suivi par élève" subtitle="Rendu et notation individuels">
                <div class="table-shell">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Élève</th>
                                <th>Statut</th>
                                <th>Note</th>
                                <th class="text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($homework->submissions as $submission)
                                <tr>
                                    <td class="font-semibold text-slate-900">{{ $submission->student->full_name }}</td>
                                    <td><span class="badge">{{ $submission->status->label() }}</span></td>
                                    <td>{{ $submission->grade !== null ? number_format((float) $submission->grade, 2, ',', ' ') . ' / 20' : '—' }}</td>
                                    <td>
                                        <div class="record-actions justify-end">
                                            @if ($submission->file_path)
                                                <a href="{{ asset('storage/' . $submission->file_path) }}" class="btn-secondary" target="_blank" rel="noopener">Fichier</a>
                                            @endif

                                            @can('update', $homework)
                                                @if ($submission->status->value === 'submitted' || $submission->status->value === 'graded')
                                                    <form method="POST" action="{{ route('homeworks.submissions.grade', [$homework, $submission]) }}" class="inline-flex flex-wrap items-center gap-2">
                                                        @csrf
                                                        <input type="number" name="grade" min="0" max="20" step="0.25" class="field w-20" placeholder="Note" value="{{ old('grade', $submission->grade) }}" required>
                                                        <button type="submit" class="btn-primary">Noter</button>
                                                    </form>
                                                @endif
                                            @endcan

                                            @if (auth()->user()->hasRole(\App\Enums\UserRole::Parent) && $parentChildren->contains($submission->student_id) && $submission->status->value === 'pending')
                                                <form method="POST" action="{{ route('homeworks.submissions.store', $homework) }}" enctype="multipart/form-data" class="inline-flex flex-wrap items-center gap-2">
                                                    @csrf
                                                    <input type="hidden" name="student_id" value="{{ $submission->student_id }}">
                                                    <input type="file" name="file" class="field text-sm max-w-[12rem]">
                                                    <button type="submit" class="btn-primary">Rendre</button>
                                                </form>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </x-content-panel>
        </div>

        <aside>
            <x-content-panel title="Informations" subtitle="Enseignant et échéance">
                <dl class="space-y-3 text-sm">
                    <div class="flex justify-between gap-4">
                        <dt class="text-slate-500">Enseignant</dt>
                        <dd class="font-medium text-slate-900">{{ $homework->teacher->name }}</dd>
                    </div>
                    <div class="flex justify-between gap-4">
                        <dt class="text-slate-500">Classe</dt>
                        <dd class="font-medium text-slate-900">{{ $homework->classroom->name }}</dd>
                    </div>
                    <div class="flex justify-between gap-4">
                        <dt class="text-slate-500">Date limite</dt>
                        <dd class="font-medium @if($homework->isOverdue()) text-rose-600 @else text-emerald-700 @endif">
                            {{ $homework->due_date->format('d/m/Y H:i') }}
                        </dd>
                    </div>
                    <div class="flex justify-between gap-4">
                        <dt class="text-slate-500">Rendus</dt>
                        <dd class="font-medium text-slate-900">
                            {{ $homework->submissions->whereIn('status', ['submitted', 'graded'])->count() }}
                            / {{ $homework->submissions->count() }}
                        </dd>
                    </div>
                </dl>
            </x-content-panel>
        </aside>
    </div>
@endsection
