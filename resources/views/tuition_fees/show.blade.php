@extends('layouts.app')

@section('title', $fee->level.' | SchoolGood')
@section('topbar_title', 'Frais de scolarité')

@section('content')
    @include('partials.page-header', ['title' => $fee->level.' — '.$fee->section?->label()])

    <section class="surface-card mt-6 max-w-xl p-5 lg:p-6 space-y-3 text-sm">
        <p><strong>Inscription :</strong> {{ number_format((float) $fee->registration_fee, 0, ',', ' ') }} FCFA</p>
        <p><strong>1ère tranche :</strong> {{ number_format((float) $fee->first_installment, 0, ',', ' ') }} FCFA</p>
        <p><strong>2ème tranche :</strong> {{ number_format((float) $fee->second_installment, 0, ',', ' ') }} FCFA</p>
        <p><strong>3ème tranche :</strong> {{ number_format((float) $fee->third_installment, 0, ',', ' ') }} FCFA</p>
        <p class="font-bold text-lg">Total : {{ number_format($fee->totalAnnualFees(), 0, ',', ' ') }} FCFA</p>
        <div class="flex gap-3 pt-4">
            <a href="{{ route('tuition-fees.index') }}" class="btn-secondary">Retour</a>
            @can('update', $fee)
                <a href="{{ route('tuition-fees.edit', $fee) }}" class="btn-primary">Modifier</a>
            @endcan
        </div>
    </section>
@endsection
