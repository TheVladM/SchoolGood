@extends('layouts.app')

@section('title', 'Modifier emploi du temps | SchoolGood')
@section('topbar_title', 'Emploi du temps')

@section('content')
    <x-form-shell
        title="Modifier le créneau"
        :action="route('timetable-entries.update', $entry)"
        method="PUT"
        :cancel-url="route('timetable-entries.index')"
    >
        @include('timetable_entries._form', ['entry' => $entry])
    </x-form-shell>
@endsection
