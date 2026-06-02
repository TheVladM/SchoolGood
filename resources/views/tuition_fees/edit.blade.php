@extends('layouts.app')

@section('title', 'Modifier grille | SchoolGood')
@section('topbar_title', 'Frais de scolarité')

@section('content')
    <x-form-shell
        :title="'Modifier la grille — '.$fee->level"
        :action="route('tuition-fees.update', $fee)"
        method="PUT"
        :cancel-url="route('tuition-fees.index')"
        max-width="max-w-3xl"
    >
        @include('tuition_fees._form', ['fee' => $fee])
    </x-form-shell>
@endsection
