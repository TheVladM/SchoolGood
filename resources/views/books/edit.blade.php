@extends('layouts.app')

@section('title', __('books.edit_title') . ' | SchoolGood')
@section('topbar_title', __('nav.library'))

@section('content')
    <x-form-shell
        :title="__('books.edit_title')"
        :action="route('books.update', $book)"
        method="PUT"
        :cancel-url="route('books.show', $book)"
    >
        @include('books._form', ['book' => $book])
    </x-form-shell>
@endsection
