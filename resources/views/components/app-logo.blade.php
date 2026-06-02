@props([
    'size' => 'md',
    'showName' => true,
    'tagline' => null,
    'variant' => 'default',
])

@php
    $sizeClass = match ($size) {
        'sm' => 'app-logo--sm',
        'lg' => 'app-logo--lg',
        'xl' => 'app-logo--xl',
        default => 'app-logo--md',
    };
    $variantClass = $variant === 'light' ? 'app-logo--light' : ($variant === 'on-dark' ? 'app-logo--on-dark' : '');
@endphp

<div {{ $attributes->merge(['class' => "app-logo {$sizeClass} {$variantClass}"]) }}>
    <div class="app-logo__emblem" aria-hidden="true">
        <img
            src="{{ asset('images/schoolgood-logo.png') }}"
            alt=""
            width="240"
            height="240"
            loading="eager"
            decoding="async"
        >
    </div>
    @if ($showName || $tagline)
        <div class="app-logo__text">
            @if ($showName)
                <span class="app-logo__name">SchoolGood</span>
            @endif
            @if ($tagline)
                <span class="app-logo__tagline">{{ $tagline }}</span>
            @endif
        </div>
    @endif
</div>
