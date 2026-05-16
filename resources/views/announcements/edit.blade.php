@extends('layouts.app')

@section('content')
    <section class="panel mx-auto max-w-4xl p-6">
        <div class="mb-6">
            <h1 class="text-3xl font-black text-slate-900">Modifier le message</h1>
            <p class="mt-2 text-slate-600">Toute modification par la scolarite ou l administration repasse en attente d approbation.</p>
        </div>

        <form method="POST" action="{{ route('announcements.update', $announcement) }}" class="space-y-6">
            @csrf
            @method('PUT')
            @include('announcements._form')

            <div class="flex gap-3">
                <button type="submit" class="btn-primary">Mettre a jour</button>
                <a href="{{ route('announcements.index') }}" class="btn-secondary">Annuler</a>
            </div>
        </form>
    </section>
@endsection
