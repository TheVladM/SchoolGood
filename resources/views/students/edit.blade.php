@extends('layouts.app')

@section('title', __('students.edit_title') . ' | SchoolGood')
@section('topbar_title', __('nav.students'))

@section('content')
    <x-form-shell
        :title="__('students.edit_title') . ' — ' . $student->full_name"
        :action="route('students.update', $student)"
        method="PUT"
        :cancel-url="route('students.show', $student)"
    >
        @include('students._form')
    </x-form-shell>
@endsection
