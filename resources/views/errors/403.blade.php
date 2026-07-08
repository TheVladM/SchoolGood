@extends('layouts.app')

@section('title', __('ui.error_403_title') . ' | SchoolGood')

@section('content')
    <section class="surface-card mx-auto max-w-md mt-16 p-10 text-center" data-reveal>
        <div class="mx-auto mb-4 flex h-16 w-16 items-center justify-center rounded-2xl bg-rose-50">
            <svg class="h-8 w-8 text-rose-400" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 1 0-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 0 0 2.25-2.25v-6.75a2.25 2.25 0 0 0-2.25-2.25H6.75a2.25 2.25 0 0 0-2.25 2.25v6.75a2.25 2.25 0 0 0 2.25 2.25Z"/>
            </svg>
        </div>
        <p class="text-5xl font-black tracking-tight text-rose-100">403</p>
        <h1 class="mt-3 text-xl font-bold text-slate-900">{{ __('ui.error_403_title') }}</h1>
        <p class="mt-2 text-sm text-slate-500">{{ __('ui.error_403_desc') }}</p>
        <a href="{{ auth()->check() ? route('dashboard') : route('login') }}" class="btn-primary mt-6 inline-flex">
            {{ auth()->check() ? __('ui.return_to_dashboard') : __('ui.connect') }}
        </a>
    </section>
@endsection
