@extends('layouts.app')

@section('title', 'Modifier utilisateur | SchoolGood')
@section('topbar_title', 'Modifier utilisateur')

@section('content')
    <x-form-shell
        title="Modifier {{ $user->name }}"
        :action="route('users.update', $user)"
        method="PUT"
        :cancel-url="route('users.show', $user)"
    >
        @include('users._form', ['user' => $user])
    </x-form-shell>
@endsection
