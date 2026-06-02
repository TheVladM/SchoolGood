@extends('layouts.app')

@section('title', 'Nouvelle salle | SchoolGood')
@section('topbar_title', 'Salles')

@section('content')
    <x-form-shell
        title="Nouvelle salle"
        :action="route('rooms.store')"
        :cancel-url="route('rooms.index')"
        max-width="max-w-lg"
    >
        @include('rooms._form', ['room' => new \App\Models\Room])
    </x-form-shell>
@endsection
