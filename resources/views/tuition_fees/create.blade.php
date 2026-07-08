@extends('layouts.app')

@section('title', __('tuition_fees.create_title') . ' | SchoolGood')
@section('topbar_title', __('nav.tuition_fees'))

@section('content')
    <x-form-shell
        :title="__('tuition_fees.create_title')"
        :description="__('tuition_fees.create_desc')"
        :action="route('tuition-fees.store')"
        :cancel-url="route('tuition-fees.index')"
        max-width="max-w-3xl"
    >
        @include('tuition_fees._form', ['fee' => new \App\Models\TuitionFee])
    </x-form-shell>
@endsection
