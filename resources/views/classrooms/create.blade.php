@extends('layouts.app')

@section('title', __('classrooms.create_title') . ' | SchoolGood')
@section('topbar_title', __('nav.classrooms'))

@section('content')
    <x-form-shell
        :title="__('classrooms.create_title')"
        :description="__('classrooms.create_desc')"
        :action="route('classrooms.store')"
        :cancel-url="route('classrooms.index')"
    >
        @include('classrooms._form')
    </x-form-shell>
@endsection
