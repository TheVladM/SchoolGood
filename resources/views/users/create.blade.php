@extends('layouts.app')

@section('title', 'Nouvel utilisateur | SchoolGood')
@section('topbar_title', 'Nouvel utilisateur')

@section('content')
    <x-form-shell
        title="Créer un utilisateur"
        description="Compte d’accès et rôle."
        :action="route('users.store')"
        :cancel-url="route('users.index')"
    >
        @include('users._form')
    </x-form-shell>
@endsection
