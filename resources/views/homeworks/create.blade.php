@extends('layouts.app')

@section('title', __('homeworks.create_title') . ' | SchoolGood')
@section('topbar_title', __('nav.homeworks'))

@section('content')
    <x-form-shell
        :title="__('homeworks.create_title')"
        :description="__('homeworks.create_desc')"
        :action="route('homeworks.store')"
        :cancel-url="route('homeworks.index')"
        max-width="max-w-3xl"
    >
        @include('homeworks._form', ['homework' => null, 'classrooms' => $classrooms, 'teachers' => $teachers])
    </x-form-shell>
@endsection
