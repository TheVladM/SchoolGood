@props([
    'title',
    'description' => null,
    'statLabel' => null,
    'statValue' => null,
])

<header class="page-header" data-reveal>
    <div class="page-header__content">
        <h1 class="page-header__title">{{ $title }}</h1>
        @if ($description)
            <p class="page-header__description">{{ $description }}</p>
        @endif
    </div>
    @if ($statLabel && $statValue !== null)
        <div class="page-header__stat-card">
            <span class="page-header__stat-label">{{ $statLabel }}</span>
            <span class="page-header__stat-value">{{ $statValue }}</span>
        </div>
    @endif
    @isset($actions)
        <div class="page-header__actions">
            {{ $actions }}
        </div>
    @endisset
</header>
