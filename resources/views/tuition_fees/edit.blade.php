@extends('layouts.app')

@section('title', __('tuition_fees.edit_title') . ' | SchoolGood')
@section('topbar_title', __('nav.tuition_fees'))

@section('content')
    <x-form-shell
        :title="__('tuition_fees.edit_title') . ' · ' . $fee->level"
        :action="route('tuition-fees.update', $fee)"
        method="PUT"
        :cancel-url="route('tuition-fees.index')"
        max-width="max-w-3xl"
    >
        @include('tuition_fees._form', ['fee' => $fee])
    </x-form-shell>
@endsection
