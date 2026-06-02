@extends('layouts.app')

@section('title', 'Modifier paiement | SchoolGood')
@section('topbar_title', 'Modifier paiement')

@section('content')
    <x-form-shell
        title="Modifier le paiement"
        description="Statut, mode et montant."
        :action="route('payments.update', $payment)"
        method="PUT"
        :cancel-url="route('payments.index')"
        submit-label="Mettre à jour"
    >
        @include('payments._form')
    </x-form-shell>
@endsection
