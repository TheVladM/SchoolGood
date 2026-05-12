@extends('layouts.app')

@section('content')
    <section class="panel mx-auto max-w-4xl p-6">
        <div class="mb-6">
            <h1 class="text-3xl font-black text-slate-900">Enregistrer un paiement</h1>
            <p class="mt-2 text-slate-600">Tracez une inscription ou une tranche reglee.</p>
        </div>

        <form method="POST" action="{{ route('payments.store') }}" class="space-y-6">
            @csrf
            @include('payments._form')

            <div class="flex gap-3">
                <button type="submit" class="btn-primary">Enregistrer</button>
                <a href="{{ route('payments.index') }}" class="btn-secondary">Annuler</a>
            </div>
        </form>
    </section>
@endsection
