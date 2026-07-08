@extends('layouts.app')

@section('title', __('rooms.create_title') . ' | SchoolGood')
@section('topbar_title', __('nav.rooms'))

@section('content')
    <x-form-shell
        :title="__('rooms.create_title')"
        :description="__('rooms.create_desc')"
        :action="route('rooms.store')"
        :cancel-url="route('rooms.index')"
        max-width="max-w-lg"
    >
        @include('rooms._form', ['room' => new \App\Models\Room])
    </x-form-shell>
@endsection
