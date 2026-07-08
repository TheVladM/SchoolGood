@props([
    'title',
    'description' => null,
    'action',
    'method' => 'POST',
    'cancelUrl',
    'submitLabel' => null,
    'enctype' => null,
    'maxWidth' => 'max-w-4xl',
])

<div class="form-card {{ $maxWidth }} mx-auto mt-6" data-reveal>
    <div class="form-card__header">
        <h1 class="form-card__title">{{ $title }}</h1>
        @if ($description)
            <p class="form-card__desc">{{ $description }}</p>
        @endif
    </div>

    <form
        method="POST"
        action="{{ $action }}"
        @if ($enctype) enctype="{{ $enctype }}" @endif
    >
        @csrf
        @if (! in_array(strtoupper($method), ['POST', 'GET']))
            @method($method)
        @endif

        <div class="form-card__body space-y-6">
            {{ $slot }}
        </div>

        <div class="form-card__footer">
            <button type="submit" class="btn-primary">{{ $submitLabel ?? __('ui.save') }}</button>
            <a href="{{ $cancelUrl }}" class="btn-secondary">{{ __('ui.cancel') }}</a>
        </div>
    </form>
</div>
