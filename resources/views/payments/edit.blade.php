@extends('layouts.app')

@section('content')
    <section class="panel mx-auto max-w-4xl p-6">
        <div class="mb-6">
            <h1 class="text-3xl font-black text-slate-900">Modifier le paiement</h1>
            <p class="mt-2 text-slate-600">Ajustez le statut, le mode et le montant.</p>
        </div>

        <form method="POST" action="{{ route('payments.update', $payment) }}" class="space-y-6">
            @csrf
            @method('PUT')
            @include('payments._form')

            <div class="flex gap-3">
                <button type="submit" class="btn-primary">Mettre a jour</button>
                <a href="{{ route('payments.index') }}" class="btn-secondary">Retour</a>
            </div>
        </form>
    </section>
@endsection
