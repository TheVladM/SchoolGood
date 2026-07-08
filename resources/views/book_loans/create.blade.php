@extends('layouts.app')

@section('title', __('book_loans.create_title') . ' | SchoolGood')
@section('topbar_title', __('nav.book_loans'))

@section('content')
    <x-form-shell
        :title="__('book_loans.create_title')"
        :description="__('book_loans.create_desc')"
        :action="route('book-loans.store')"
        :cancel-url="route('book-loans.index')"
    >
        @include('book_loans._form')
    </x-form-shell>
@endsection
