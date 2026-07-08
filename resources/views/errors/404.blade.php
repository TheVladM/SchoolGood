@extends('layouts.app')

@section('title', __('ui.error_404_title') . ' | SchoolGood')

@section('content')
    <section class="surface-card mx-auto max-w-md mt-16 p-10 text-center" data-reveal>
        <div class="mx-auto mb-4 flex h-16 w-16 items-center justify-center rounded-2xl bg-slate-100">
            <svg class="h-8 w-8 text-slate-400" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9 3.75h.008v.008H12v-.008Z"/>
            </svg>
        </div>
        <p class="text-5xl font-black tracking-tight text-slate-200">404</p>
        <h1 class="mt-3 text-xl font-bold text-slate-900">{{ __('ui.error_404_title') }}</h1>
        <p class="mt-2 text-sm text-slate-500">{{ __('ui.error_404_desc') }}</p>
        <a href="{{ auth()->check() ? route('dashboard') : route('login') }}" class="btn-primary mt-6 inline-flex">
            {{ auth()->check() ? __('ui.return_to_dashboard') : __('ui.connect') }}
        </a>
    </section>
@endsection
