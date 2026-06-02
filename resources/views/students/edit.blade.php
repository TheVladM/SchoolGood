@extends('layouts.app')

@section('title', 'Modifier élève | SchoolGood')
@section('topbar_title', 'Modifier élève')

@section('content')
    <x-form-shell
        title="Modifier {{ $student->full_name }}"
        :action="route('students.update', $student)"
        method="PUT"
        :cancel-url="route('students.show', $student)"
    >
        @include('students._form')
    </x-form-shell>
@endsection
