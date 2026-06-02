@extends('layouts.app')

@section('title', 'Éditer le devoir | SchoolGood')
@section('topbar_title', 'Éditer: '.$homework->title)

@section('content')
    <x-form-shell
        title="Éditer le devoir"
        :description="$homework->title"
        :action="route('homeworks.update', $homework)"
        method="PUT"
        :cancel-url="route('homeworks.show', $homework)"
        submit-label="Mettre à jour"
        max-width="max-w-3xl"
    >
        @include('homeworks._form', ['homework' => $homework, 'classrooms' => $classrooms, 'teachers' => $teachers])
    </x-form-shell>
@endsection
