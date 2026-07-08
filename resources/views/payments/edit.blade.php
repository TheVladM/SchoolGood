@extends('layouts.app')

@section('title', __('payments.edit_title') . ' | SchoolGood')
@section('topbar_title', __('nav.payments'))

@section('content')
    <x-form-shell
        :title="__('payments.edit_title')"
        :description="__('payments.edit_desc')"
        :action="route('payments.update', $payment)"
        method="PUT"
        :cancel-url="route('payments.index')"
    >
        @include('payments._form')
    </x-form-shell>
@endsection
