@extends('layouts.app')

@section('title', 'Modifier année | SchoolGood')
@section('topbar_title', 'Année scolaire')

@section('content')
    <x-form-shell
        title="Modifier {{ $schoolYear->name }}"
        :action="route('school-years.update', $schoolYear)"
        method="PUT"
        :cancel-url="route('school-years.show', $schoolYear)"
    >
        @include('school_years._form', ['schoolYear' => $schoolYear])
    </x-form-shell>
@endsection
