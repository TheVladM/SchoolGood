@props(['title' => null, 'subtitle' => null])

<section {{ $attributes->merge(['class' => 'content-panel']) }}>
    @if ($title || isset($toolbar))
        <div class="content-panel__head">
            @if ($title)
                <div>
                    <h2 class="content-panel__title">{{ $title }}</h2>
                    @if ($subtitle)
                        <p class="content-panel__subtitle">{{ $subtitle }}</p>
                    @endif
                </div>
            @endif
            @isset($toolbar)
                <div class="content-panel__toolbar">
                    {{ $toolbar }}
                </div>
            @endisset
        </div>
    @endif
    <div class="content-panel__body">
        {{ $slot }}
    </div>
</section>
