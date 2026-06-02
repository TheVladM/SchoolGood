@extends('layouts.app')

@section('title', 'Nouveau message | SchoolGood')
@section('topbar_title', 'Nouveau message')

@section('content')
    <x-form-shell
        title="Publier un message"
        description="Annonce ou communication aux parents."
        :action="route('announcements.store')"
        :cancel-url="route('announcements.index')"
    >
        @include('announcements._form')
    </x-form-shell>
@endsection
