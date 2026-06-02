@props([
    'label',
    'name',
    'type' => 'text',
    'hint' => null,
    'required' => false,
])

<div class="form-field" {{ $attributes->only('class') }}>
    <label for="{{ $name }}" class="label">
        {{ $label }}
        @if ($required)
            <span class="text-rose-500" aria-hidden="true">*</span>
        @endif
    </label>

    @if ($type === 'slot')
        {{ $slot }}
    @else
        <input
            id="{{ $name }}"
            name="{{ $name }}"
            type="{{ $type }}"
            @if ($required) required @endif
            {{ $attributes->except('class')->merge(['class' => 'field']) }}
        >
    @endif

    @if ($hint)
        <p class="form-field__hint">{{ $hint }}</p>
    @endif

    @error($name)
        <p class="form-field__error" role="alert">{{ $message }}</p>
    @enderror
</div>
