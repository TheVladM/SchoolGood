@extends('layouts.app')

@section('title', 'Modifier classe | SchoolGood')
@section('topbar_title', 'Modifier classe')

@section('content')
    <x-form-shell
        title="Modifier {{ $classroom->name }}"
        :action="route('classrooms.update', $classroom)"
        method="PUT"
        :cancel-url="route('classrooms.show', $classroom)"
    >
        @include('classrooms._form', ['classroom' => $classroom])
    </x-form-shell>
@endsection
