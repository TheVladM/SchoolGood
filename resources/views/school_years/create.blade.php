@extends('layouts.app')

@section('title', 'Nouvelle année | SchoolGood')
@section('topbar_title', 'Année scolaire')

@section('content')
    <x-form-shell
        title="Créer une année scolaire"
        :action="route('school-years.store')"
        :cancel-url="route('school-years.index')"
    >
        @include('school_years._form')
    </x-form-shell>
@endsection
