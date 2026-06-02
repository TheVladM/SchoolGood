@extends('layouts.app')

@section('title', 'Nouvelle classe | SchoolGood')
@section('topbar_title', 'Nouvelle classe')

@section('content')
    <x-form-shell
        title="Créer une classe"
        description="Section, salle, enseignants titulaire et de langue."
        :action="route('classrooms.store')"
        :cancel-url="route('classrooms.index')"
    >
        @include('classrooms._form')
    </x-form-shell>
@endsection
