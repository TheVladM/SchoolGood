@extends('layouts.app')

@section('title', 'Modifier emprunt | SchoolGood')
@section('topbar_title', 'Emprunt')

@section('content')
    <x-form-shell
        title="Modifier l’emprunt"
        :action="route('book-loans.update', $bookLoan)"
        method="PUT"
        :cancel-url="route('book-loans.show', $bookLoan)"
    >
        @include('book_loans._form', ['bookLoan' => $bookLoan])
    </x-form-shell>
@endsection
