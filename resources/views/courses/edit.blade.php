@extends('layouts.app')

@section('title', 'Modifier cours | SchoolGood')
@section('topbar_title', 'Modifier cours')

@section('content')
    <x-form-shell
        title="Modifier le cours"
        :action="route('courses.update', $course)"
        method="PUT"
        :cancel-url="route('courses.show', $course)"
    >
        @include('courses._form', ['course' => $course])
    </x-form-shell>
@endsection
