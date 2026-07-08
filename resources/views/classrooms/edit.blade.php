@extends('layouts.app')

@section('title', __('classrooms.edit_title') . ' | SchoolGood')
@section('topbar_title', __('nav.classrooms'))

@section('content')
    <x-form-shell
        :title="__('classrooms.edit_title') . ' – ' . $classroom->name"
        :action="route('classrooms.update', $classroom)"
        method="PUT"
        :cancel-url="route('classrooms.show', $classroom)"
    >
        @include('classrooms._form', ['classroom' => $classroom])
    </x-form-shell>
@endsection
