@extends('layouts.app')

@section('title', __('timetable.create_title') . ' | SchoolGood')
@section('topbar_title', __('nav.timetable'))

@section('content')
    <x-form-shell
        :title="__('timetable.create_title')"
        :description="__('timetable.create_desc')"
        :action="route('timetable-entries.store')"
        :cancel-url="route('timetable-entries.index')"
    >
        @include('timetable_entries._form')
    </x-form-shell>
@endsection
