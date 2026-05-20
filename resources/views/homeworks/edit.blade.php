@extends('layouts.app')

@section('title', 'Éditer le devoir | SchoolGood')
@section('topbar_title', 'Éditer: ' . $homework->title)

@section('content')
    <section class="surface-card p-5 lg:p-6 max-w-3xl mx-auto mt-6" data-reveal>
        <div class="mb-6">
            <h1 class="section-title">Éditer le devoir</h1>
            <p class="section-subtitle">{{ $homework->title }}</p>
        </div>

        @include('homeworks._form', [
            'homework' => $homework,
            'action' => route('homeworks.update', $homework),
            'method' => 'PATCH',
        ])
    </section>
@endsection
