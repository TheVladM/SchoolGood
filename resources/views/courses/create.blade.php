@extends('layouts.app')

@section('title', __('courses.create_title') . ' | SchoolGood')
@section('topbar_title', __('nav.courses'))

@section('content')
    <x-form-shell
        :title="__('courses.create_title')"
        :description="__('courses.create_desc')"
        :action="route('courses.store')"
        :cancel-url="route('courses.index')"
    >
        @include('courses._form')
    </x-form-shell>
@endsection
