@extends('layouts.app')

@section('title', __('rooms.edit_title') . ' | SchoolGood')
@section('topbar_title', __('nav.rooms'))

@section('content')
    <x-form-shell
        :title="__('rooms.edit_title')"
        :action="route('rooms.update', $room)"
        method="PUT"
        :cancel-url="route('rooms.index')"
        max-width="max-w-lg"
    >
        @include('rooms._form', ['room' => $room])
    </x-form-shell>
@endsection
