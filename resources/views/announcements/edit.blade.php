@extends('layouts.app')

@section('title', 'Modifier message | SchoolGood')
@section('topbar_title', 'Modifier message')

@section('content')
    <x-form-shell
        title="Modifier le message"
        :action="route('announcements.update', $announcement)"
        method="PUT"
        :cancel-url="route('announcements.show', $announcement)"
    >
        @include('announcements._form', ['announcement' => $announcement])
    </x-form-shell>
@endsection
