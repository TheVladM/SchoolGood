@extends('layouts.app')

@section('title', __('users.edit_title') . ' | SchoolGood')
@section('topbar_title', __('nav.users'))

@section('content')
    <x-form-shell
        :title="__('users.edit_title') . ' – ' . $user->name"
        :action="route('users.update', $user)"
        method="PUT"
        :cancel-url="route('users.show', $user)"
    >
        @include('users._form', ['user' => $user])
    </x-form-shell>
@endsection
