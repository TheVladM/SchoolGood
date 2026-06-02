@extends('layouts.app')

@section('title', 'Nouvelle grille | SchoolGood')
@section('topbar_title', 'Frais de scolarité')

@section('content')
    <x-form-shell
        title="Nouvelle grille tarifaire"
        :action="route('tuition-fees.store')"
        :cancel-url="route('tuition-fees.index')"
        max-width="max-w-3xl"
    >
        @include('tuition_fees._form', ['fee' => new \App\Models\TuitionFee])
    </x-form-shell>
@endsection
