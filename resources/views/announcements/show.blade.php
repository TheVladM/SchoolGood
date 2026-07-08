@extends('layouts.app')

@section('title', $announcement->title.' | SchoolGood')
@section('topbar_title', __('nav.messages'))

@section('content')
    @php
        $statusBadge = match ($announcement->status?->value) {
            'approved'         => 'badge--success',
            'pending_approval' => 'badge--warning',
            'rejected'         => 'badge--danger',
            default            => '',
        };
    @endphp

    @include('partials.page-header', ['title' => $announcement->title, 'description' => $announcement->status?->label()])

    <section class="mt-6 grid gap-6 lg:grid-cols-[0.9fr_1.1fr]" data-reveal>

        {{-- Métadonnées --}}
        <article class="surface-card p-5 lg:p-6">
            <div class="entity-header">
                <div class="entity-header__icon" style="background:#ede9fe;color:#7c3aed;border-radius:14px;">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" style="width:1.4rem;height:1.4rem;" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 0 1-2.25 2.25h-15a2.25 2.25 0 0 1-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25m19.5 0v.243a2.25 2.25 0 0 1-1.07 1.916l-7.5 4.615a2.25 2.25 0 0 1-2.36 0L3.32 8.91a2.25 2.25 0 0 1-1.07-1.916V6.75"/></svg>
                </div>
                <div class="flex-1">
                    <p class="entity-header__name">{{ $announcement->title }}</p>
                    <p class="entity-header__meta">{{ $announcement->author?->name }}</p>
                </div>
                <span class="badge {{ $statusBadge }}">{{ $announcement->status?->label() }}</span>
            </div>

            <div class="info-list">
                <div class="info-row">
                    <span class="info-key">{{ __('announcements.info_audience') }}</span>
                    <span class="info-val">{{ $announcement->audience?->label() ?: '—' }}</span>
                </div>
                <div class="info-row">
                    <span class="info-key">{{ __('announcements.info_classroom') }}</span>
                    <span class="info-val">{{ $announcement->classroom?->name ?: '—' }}</span>
                </div>
                <div class="info-row">
                    <span class="info-key">{{ __('announcements.info_parent') }}</span>
                    <span class="info-val">{{ $announcement->parent?->name ?: '—' }}</span>
                </div>
                <div class="info-row">
                    <span class="info-key">{{ __('announcements.info_author') }}</span>
                    <span class="info-val">{{ $announcement->author?->name ?: '—' }}</span>
                </div>
                <div class="info-row">
                    <span class="info-key">{{ __('announcements.info_approved_by') }}</span>
                    <span class="info-val">{{ $announcement->approver?->name ?: '—' }}</span>
                </div>
                <div class="info-row">
                    <span class="info-key">{{ __('announcements.info_approved_at') }}</span>
                    <span class="info-val">{{ $announcement->approved_at?->format('d/m/Y H:i') ?: '—' }}</span>
                </div>
            </div>

            <div class="mt-6 flex flex-wrap gap-3">
                <a href="{{ route('announcements.index') }}" class="btn-secondary">{{ __('ui.back') }}</a>

                @if (
                    auth()->user()->hasRole(\App\Enums\UserRole::Founder) ||
                    auth()->id() === $announcement->author_id
                )
                    <a href="{{ route('announcements.edit', $announcement) }}" class="btn-primary">{{ __('ui.edit') }}</a>
                @endif

                @can('approve', $announcement)
                    <form method="POST" action="{{ route('announcements.approve', $announcement) }}">
                        @csrf
                        <button type="submit" class="btn-primary">{{ __('announcements.approve_btn') }}</button>
                    </form>
                @endcan

                @can('reject', $announcement)
                    <form method="POST" action="{{ route('announcements.reject', $announcement) }}">
                        @csrf
                        <button type="submit" class="btn-danger">{{ __('announcements.reject_btn') }}</button>
                    </form>
                @endcan
            </div>
        </article>

        {{-- Contenu + accusés de lecture --}}
        <article class="surface-card p-5 lg:p-6 space-y-6">
            <div>
                <h2 class="section-heading">{{ __('announcements.content') }}</h2>
                <div class="mt-3 rounded-xl bg-slate-50 p-4 text-sm leading-7 text-slate-600 whitespace-pre-line">
                    {{ $announcement->content }}
                </div>
            </div>

            @if ($canSeeReadReceipts && $recipients->isNotEmpty())
                <div>
                    <h2 class="section-heading">
                        {{ __('announcements.read_receipts') }}
                        <span class="text-slate-400 font-normal text-sm ml-1">
                            ({{ $readUserIds->count() }}/{{ $recipients->count() }})
                        </span>
                    </h2>
                    <ul class="space-y-2 text-sm">
                        @foreach ($recipients as $recipient)
                            @php $read = $announcement->reads->firstWhere('user_id', $recipient->id); @endphp
                            <li class="flex items-center justify-between rounded-lg border border-slate-100 px-3 py-2">
                                <span class="text-slate-700">{{ $recipient->name }}</span>
                                @if ($read)
                                    <span class="badge badge--success">{{ __('announcements.read_label') }} {{ $read->read_at->format('d/m/Y H:i') }}</span>
                                @else
                                    <span class="badge">{{ __('announcements.not_read') }}</span>
                                @endif
                            </li>
                        @endforeach
                    </ul>
                </div>
            @elseif (auth()->user()->hasRole(\App\Enums\UserRole::Parent))
                <p class="text-sm text-emerald-700">{{ __('announcements.parent_read_note') }}</p>
            @endif
        </article>
    </section>
@endsection
