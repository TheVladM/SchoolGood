@extends('layouts.app')

@section('title', 'Modifier livre | SchoolGood')
@section('topbar_title', 'Bibliothèque')

@section('content')
    <x-form-shell
        title="Modifier le livre"
        :action="route('books.update', $book)"
        method="PUT"
        :cancel-url="route('books.show', $book)"
    >
        @include('books._form', ['book' => $book])
    </x-form-shell>
@endsection
