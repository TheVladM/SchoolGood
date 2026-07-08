@extends('layouts.app')

@section('title', __('school_years.edit_title') . ' | SchoolGood')
@section('topbar_title', __('nav.school_years'))

@section('content')
    <x-form-shell
        :title="__('school_years.edit_title') . ' – ' . $schoolYear->name"
        :action="route('school-years.update', $schoolYear)"
        method="PUT"
        :cancel-url="route('school-years.show', $schoolYear)"
    >
        @include('school_years._form', ['schoolYear' => $schoolYear])
    </x-form-shell>
@endsection
