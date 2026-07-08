@extends('layouts.app')

@section('title', __('books.create_title') . ' | SchoolGood')
@section('topbar_title', __('nav.library'))

@section('content')
    <x-form-shell
        :title="__('books.create_title')"
        :description="__('books.create_desc')"
        :action="route('books.store')"
        :cancel-url="route('books.index')"
    >
        @include('books._form')
    </x-form-shell>
@endsection
