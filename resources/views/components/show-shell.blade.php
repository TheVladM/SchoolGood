@props([
    'title',
    'description' => null,
    'backUrl' => null,
    'backLabel' => 'Retour',
])

@include('partials.page-header', [
    'title' => $title,
    'description' => $description,
])

<div class="mt-6 space-y-6" data-reveal>
    {{ $slot }}

    @if ($backUrl)
        <a href="{{ $backUrl }}" class="btn-secondary inline-flex">{{ $backLabel }}</a>
    @endif
</div>
