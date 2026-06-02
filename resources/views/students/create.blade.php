@extends('layouts.app')

@section('title', 'Nouvel élève | SchoolGood')
@section('topbar_title', 'Nouvel élève')

@section('content')
    <x-form-shell
        title="Créer un élève"
        description="Rattachez l'élève à une classe et à son parent responsable."
        :action="route('students.store')"
        :cancel-url="route('students.index')"
    >
        @include('students._form')
    </x-form-shell>
@endsection
