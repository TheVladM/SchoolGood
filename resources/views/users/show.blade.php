@extends('layouts.app')

@section('title', $managedUser->name.' | SchoolGood')
@section('topbar_title', 'Utilisateur')

@section('content')
    <x-show-shell
        :title="$managedUser->name"
        :description="$managedUser->role?->label()"
        :back-url="route('users.index')"
    >
        <section class="grid gap-6 lg:grid-cols-[0.9fr_1.1fr]">
            <article class="surface-card p-5 lg:p-6">
                <span class="badge">{{ $managedUser->role?->label() }}</span>
                <div class="mt-6 space-y-3 text-sm text-slate-600">
                    <p><span class="font-semibold text-slate-900">Email :</span> {{ $managedUser->email }}</p>
                    <p><span class="font-semibold text-slate-900">Téléphone :</span> {{ $managedUser->phone ?: '—' }}</p>
                    <p><span class="font-semibold text-slate-900">Service :</span> {{ $managedUser->department?->label() ?: '—' }}</p>
                    <p><span class="font-semibold text-slate-900">Fonction :</span> {{ $managedUser->job_title ?: '—' }}</p>
                </div>
            </article>

            <article class="surface-card p-5 lg:p-6">
                <h2 class="text-xl font-bold text-slate-900">Indicateurs</h2>
                <div class="mt-5 grid gap-4 md:grid-cols-3">
                    <div class="rounded-2xl bg-slate-50 p-4">
                        <p class="text-sm text-slate-500">Enfants</p>
                        <p class="mt-2 text-3xl font-black text-slate-900">{{ $managedUser->children_count }}</p>
                    </div>
                    <div class="rounded-2xl bg-slate-50 p-4">
                        <p class="text-sm text-slate-500">Cours</p>
                        <p class="mt-2 text-3xl font-black text-slate-900">{{ $managedUser->courses_count }}</p>
                    </div>
                    <div class="rounded-2xl bg-slate-50 p-4">
                        <p class="text-sm text-slate-500">Classes principales</p>
                        <p class="mt-2 text-3xl font-black text-slate-900">{{ $managedUser->main_classrooms_count }}</p>
                    </div>
                </div>
                @can('update', $managedUser)
                    <a href="{{ route('users.edit', $managedUser) }}" class="btn-primary mt-6 inline-flex">Modifier</a>
                @endcan
            </article>
        </section>
    </x-show-shell>
@endsection
