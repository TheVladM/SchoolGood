@extends('layouts.app')

@section('title', 'Créer un devoir | SchoolGood')
@section('topbar_title', 'Nouveau devoir')

@section('content')
    <x-form-shell
        title="Créer un devoir"
        description="Assignez un devoir à une classe avec une date limite."
        :action="route('homeworks.store')"
        :cancel-url="route('homeworks.index')"
        submit-label="Créer le devoir"
        max-width="max-w-3xl"
    >
        @include('homeworks._form', ['homework' => null, 'classrooms' => $classrooms, 'teachers' => $teachers])
    </x-form-shell>
@endsection
