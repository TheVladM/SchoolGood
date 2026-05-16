@extends('layouts.app')

@section('content')
    <section class="grid gap-6 lg:grid-cols-[0.9fr_1.1fr]">
        <article class="panel p-6">
            <span class="badge">{{ $announcement->status?->label() }}</span>
            <h1 class="mt-4 text-3xl font-black text-slate-900">{{ $announcement->title }}</h1>
            <div class="mt-6 space-y-3 text-sm text-slate-600">
                <p><span class="font-semibold text-slate-900">Audience:</span> {{ $announcement->audience?->label() }}</p>
                <p><span class="font-semibold text-slate-900">Classe:</span> {{ $announcement->classroom?->name ?: 'Toutes les familles' }}</p>
                <p><span class="font-semibold text-slate-900">Auteur:</span> {{ $announcement->author?->name ?: '-' }}</p>
                <p><span class="font-semibold text-slate-900">Approuve par:</span> {{ $announcement->approver?->name ?: '-' }}</p>
                <p><span class="font-semibold text-slate-900">Date d approbation:</span> {{ $announcement->approved_at?->format('d/m/Y H:i') ?: '-' }}</p>
            </div>
        </article>

        <article class="panel p-6">
            <h2 class="text-xl font-bold text-slate-900">Contenu</h2>
            <div class="mt-5 rounded-2xl bg-slate-50 p-4 text-sm leading-7 text-slate-600">
                {{ $announcement->content }}
            </div>

            <div class="mt-6 flex flex-wrap gap-3">
                <a href="{{ route('announcements.index') }}" class="btn-secondary">Retour</a>

                @if (
                    auth()->user()->hasRole(\App\Enums\UserRole::Founder) ||
                    auth()->id() === $announcement->author_id
                )
                    <a href="{{ route('announcements.edit', $announcement) }}" class="btn-primary">Modifier</a>
                @endif

                @if (auth()->user()->hasRole(\App\Enums\UserRole::Founder) && $announcement->status !== \App\Enums\AnnouncementStatus::Approved)
                    <form method="POST" action="{{ route('announcements.approve', $announcement) }}">
                        @csrf
                        <button type="submit" class="btn-primary">Approuver</button>
                    </form>
                @endif

                @if (auth()->user()->hasRole(\App\Enums\UserRole::Founder) && $announcement->status !== \App\Enums\AnnouncementStatus::Rejected)
                    <form method="POST" action="{{ route('announcements.reject', $announcement) }}">
                        @csrf
                        <button type="submit" class="btn-danger">Invalider</button>
                    </form>
                @endif
            </div>
        </article>
    </section>
@endsection
