@extends('layouts.app')

@section('title', 'Nouvel emploi du temps | SchoolGood')
@section('topbar_title', 'Emploi du temps')

@section('content')
    <x-form-shell
        title="Créer un créneau"
        description="Partagé par toutes les classes du même niveau et de la même section."
        :action="route('timetable-entries.store')"
        :cancel-url="route('timetable-entries.index')"
    >
        @include('timetable_entries._form')
    </x-form-shell>
@endsection
