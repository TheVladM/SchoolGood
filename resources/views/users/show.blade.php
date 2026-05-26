@extends('layouts.app')

@section('content')
    <section class="grid gap-6 lg:grid-cols-[0.9fr_1.1fr]">
        <article class="panel p-6">
            <span class="badge">{{ $managedUser->role?->label() }}</span>
            <h1 class="mt-4 text-3xl font-black text-slate-900">{{ $managedUser->name }}</h1>
            <div class="mt-6 space-y-3 text-sm text-slate-600">
                <p><span class="font-semibold text-slate-900">Email:</span> {{ $managedUser->email }}</p>
                <p><span class="font-semibold text-slate-900">Telephone:</span> {{ $managedUser->phone ?: '-' }}</p>
                <p><span class="font-semibold text-slate-900">Service:</span> {{ $managedUser->department?->label() ?: '-' }}</p>
                <p><span class="font-semibold text-slate-900">Fonction:</span> {{ $managedUser->job_title ?: '-' }}</p>
            </div>
        </article>

        <article class="panel p-6">
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

            <div class="mt-6 flex gap-3">
                @can('update', $managedUser)
                    <a href="{{ route('users.edit', $managedUser) }}" class="btn-primary">Modifier</a>
                @endcan
                <a href="{{ route('users.index') }}" class="btn-secondary">Retour</a>
            </div>
        </article>
    </section>
@endsection
