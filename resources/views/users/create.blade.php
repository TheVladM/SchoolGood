@extends('layouts.app')

@section('title', __('users.create_title') . ' | SchoolGood')
@section('topbar_title', __('nav.users'))

@section('content')
    <x-form-shell
        :title="__('users.create_title')"
        :description="__('users.create_desc')"
        :action="route('users.store')"
        :cancel-url="route('users.index')"
    >
        @include('users._form')
    </x-form-shell>
@endsection
