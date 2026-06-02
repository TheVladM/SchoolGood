@extends('layouts.app')

@section('title', 'Nouveau cours | SchoolGood')
@section('topbar_title', 'Nouveau cours')

@section('content')
    <x-form-shell
        title="Créer un cours"
        description="Affectez le cours à une classe, un enseignant et un jour."
        :action="route('courses.store')"
        :cancel-url="route('courses.index')"
    >
        @include('courses._form')
    </x-form-shell>
@endsection
