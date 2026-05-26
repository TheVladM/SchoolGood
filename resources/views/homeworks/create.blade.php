@extends('layouts.app')

@section('title', 'Créer un devoir | SchoolGood')
@section('topbar_title', 'Nouveau devoir')

@section('content')
    <section class="surface-card p-5 lg:p-6 max-w-3xl mx-auto mt-6" data-reveal>
        <div class="mb-6">
            <h1 class="section-title">Créer un nouveau devoir</h1>
            <p class="section-subtitle">Assignez un devoir à une classe avec une date limite.</p>
        </div>

        @include('homeworks._form', [
            'homework' => null,
            'action' => route('homeworks.store'),
            'method' => 'POST',
            'classrooms' => $classrooms,
            'teachers' => $teachers,
        ])
    </section>
@endsection
