@extends('layouts.app')

@section('title', __('announcements.create_title') . ' | SchoolGood')
@section('topbar_title', __('nav.messages'))

@section('content')
    <x-form-shell
        :title="__('announcements.create_title')"
        :description="__('announcements.create_desc')"
        :action="route('announcements.store')"
        :cancel-url="route('announcements.index')"
    >
        @include('announcements._form')
    </x-form-shell>
@endsection
