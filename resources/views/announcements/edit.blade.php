@extends('layouts.app')

@section('title', __('announcements.edit_title') . ' | SchoolGood')
@section('topbar_title', __('nav.messages'))

@section('content')
    <x-form-shell
        :title="__('announcements.edit_title')"
        :action="route('announcements.update', $announcement)"
        method="PUT"
        :cancel-url="route('announcements.show', $announcement)"
    >
        @include('announcements._form', ['announcement' => $announcement])
    </x-form-shell>
@endsection
