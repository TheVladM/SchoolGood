@extends('layouts.app')

@section('title', 'Nouvel emprunt | SchoolGood')
@section('topbar_title', 'Emprunt')

@section('content')
    <x-form-shell
        title="Enregistrer un emprunt"
        :action="route('book-loans.store')"
        :cancel-url="route('book-loans.index')"
    >
        @include('book_loans._form')
    </x-form-shell>
@endsection
