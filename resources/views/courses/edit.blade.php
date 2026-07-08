@extends('layouts.app')

@section('title', __('courses.edit_title') . ' | SchoolGood')
@section('topbar_title', __('nav.courses'))

@section('content')
    <x-form-shell
        :title="__('courses.edit_title')"
        :action="route('courses.update', $course)"
        method="PUT"
        :cancel-url="route('courses.show', $course)"
    >
        @include('courses._form', ['course' => $course])
    </x-form-shell>
@endsection
