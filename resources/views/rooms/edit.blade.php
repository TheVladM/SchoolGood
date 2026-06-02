@extends('layouts.app')

@section('title', 'Modifier salle | SchoolGood')
@section('topbar_title', 'Salles')

@section('content')
    <x-form-shell
        title="Modifier la salle"
        :action="route('rooms.update', $room)"
        method="PUT"
        :cancel-url="route('rooms.index')"
        max-width="max-w-lg"
    >
        @include('rooms._form', ['room' => $room])
    </x-form-shell>
@endsection
