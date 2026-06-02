@extends('layouts.app')

@section('title', 'Nouveau livre | SchoolGood')
@section('topbar_title', 'Bibliothèque')

@section('content')
    <x-form-shell
        title="Ajouter un livre"
        :action="route('books.store')"
        :cancel-url="route('books.index')"
    >
        @include('books._form')
    </x-form-shell>
@endsection
