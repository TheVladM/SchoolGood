@extends('layouts.app')

@section('title', __('homeworks.edit_title') . ' | SchoolGood')
@section('topbar_title', __('nav.homeworks'))

@section('content')
    <x-form-shell
        :title="__('homeworks.edit_title')"
        :description="$homework->title"
        :action="route('homeworks.update', $homework)"
        method="PUT"
        :cancel-url="route('homeworks.show', $homework)"
        max-width="max-w-3xl"
    >
        @include('homeworks._form', ['homework' => $homework, 'classrooms' => $classrooms, 'teachers' => $teachers])
    </x-form-shell>
@endsection
