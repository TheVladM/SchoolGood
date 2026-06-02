@extends('layouts.app')

@section('content')
    @include('partials.page-header', ['title' => $announcement->title, 'description' => $announcement->status?->label()])

    <section class="mt-6 grid gap-6 lg:grid-cols-[0.9fr_1.1fr]" data-reveal>
        <article class="surface-card p-5 lg:p-6">
            <span class="badge">{{ $announcement->status?->label() }}</span>
            <h1 class="mt-4 text-3xl font-black text-slate-900">{{ $announcement->title }}</h1>
            <div class="mt-6 space-y-3 text-sm text-slate-600">
                <p><span class="font-semibold text-slate-900">Audience:</span> {{ $announcement->audience?->label() }}</p>
                <p><span class="font-semibold text-slate-900">Classe:</span> {{ $announcement->classroom?->name ?: '-' }}</p>
                <p><span class="font-semibold text-slate-900">Parent:</span> {{ $announcement->parent?->name ?: '-' }}</p>
                <p><span class="font-semibold text-slate-900">Auteur:</span> {{ $announcement->author?->name ?: '-' }}</p>
                <p><span class="font-semibold text-slate-900">Approuve par:</span> {{ $announcement->approver?->name ?: '-' }}</p>
                <p><span class="font-semibold text-slate-900">Date d approbation:</span> {{ $announcement->approved_at?->format('d/m/Y H:i') ?: '-' }}</p>
            </div>
        </article>

        <article class="surface-card p-5 lg:p-6">
            <h2 class="text-xl font-bold text-slate-900">Contenu</h2>
            <div class="mt-5 rounded-2xl bg-slate-50 p-4 text-sm leading-7 text-slate-600">
                {{ $announcement->content }}
            </div>

            @if ($canSeeReadReceipts && $recipients->isNotEmpty())
                <h2 class="mt-8 text-xl font-bold text-slate-900">Accusés de lecture</h2>
                <p class="mt-1 text-sm text-slate-500">
                    {{ $readUserIds->count() }} / {{ $recipients->count() }} parent(s) ont ouvert le message.
                </p>
                <ul class="mt-4 space-y-2 text-sm">
                    @foreach ($recipients as $recipient)
                        @php $read = $announcement->reads->firstWhere('user_id', $recipient->id); @endphp
                        <li class="flex justify-between rounded-lg border border-slate-100 px-3 py-2">
                            <span>{{ $recipient->name }}</span>
                            @if ($read)
                                <span class="text-emerald-700">Lu le {{ $read->read_at->format('d/m/Y H:i') }}</span>
                            @else
                                <span class="text-slate-400">Non lu</span>
                            @endif
                        </li>
                    @endforeach
                </ul>
            @elseif (auth()->user()->hasRole(\App\Enums\UserRole::Parent))
                <p class="mt-6 text-sm text-emerald-700">Accusé de lecture enregistré à l’ouverture de ce message.</p>
            @endif

            <div class="mt-6 flex flex-wrap gap-3">
                <a href="{{ route('announcements.index') }}" class="btn-secondary">Retour</a>

                @if (
                    auth()->user()->hasRole(\App\Enums\UserRole::Founder) ||
                    auth()->id() === $announcement->author_id
                )
                    <a href="{{ route('announcements.edit', $announcement) }}" class="btn-primary">Modifier</a>
                @endif

                @can('approve', $announcement)
                    <form method="POST" action="{{ route('announcements.approve', $announcement) }}">
                        @csrf
                        <button type="submit" class="btn-primary">Approuver</button>
                    </form>
                @endcan

                @can('reject', $announcement)
                    <form method="POST" action="{{ route('announcements.reject', $announcement) }}">
                        @csrf
                        <button type="submit" class="btn-danger">Refuser</button>
                    </form>
                @endcan
            </div>
        </article>
    </section>
@endsection
