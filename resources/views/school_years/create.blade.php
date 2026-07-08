@extends('layouts.app')

@section('title', __('school_years.create_title') . ' | SchoolGood')
@section('topbar_title', __('nav.school_years'))

@section('content')
    <x-form-shell
        :title="__('school_years.create_title')"
        :description="__('school_years.create_desc')"
        :action="route('school-years.store')"
        :cancel-url="route('school-years.index')"
    >
        @include('school_years._form')
    </x-form-shell>
@endsection
