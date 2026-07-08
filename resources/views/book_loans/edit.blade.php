@extends('layouts.app')

@section('title', __('book_loans.edit_title') . ' | SchoolGood')
@section('topbar_title', __('nav.book_loans'))

@section('content')
    <x-form-shell
        :title="__('book_loans.edit_title')"
        :action="route('book-loans.update', $bookLoan)"
        method="PUT"
        :cancel-url="route('book-loans.show', $bookLoan)"
    >
        @include('book_loans._form', ['bookLoan' => $bookLoan])
    </x-form-shell>
@endsection
