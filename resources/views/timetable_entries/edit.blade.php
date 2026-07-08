@extends('layouts.app')

@section('title', __('timetable.edit_title') . ' | SchoolGood')
@section('topbar_title', __('nav.timetable'))

@section('content')
    <x-form-shell
        :title="__('timetable.edit_title')"
        :action="route('timetable-entries.update', $entry)"
        method="PUT"
        :cancel-url="route('timetable-entries.index')"
    >
        @include('timetable_entries._form', ['entry' => $entry])
    </x-form-shell>
@endsection
