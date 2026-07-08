@extends('layouts.app')

@section('title', __('students.create_title') . ' | SchoolGood')
@section('topbar_title', __('nav.students'))

@section('content')
    <x-form-shell
        :title="__('students.create_title')"
        :description="__('students.create_desc')"
        :action="route('students.store')"
        :cancel-url="route('students.index')"
    >
        @include('students._form')
    </x-form-shell>
@endsection
