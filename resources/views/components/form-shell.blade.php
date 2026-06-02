@props([
    'title',
    'description' => null,
    'action',
    'method' => 'POST',
    'cancelUrl',
    'submitLabel' => 'Enregistrer',
    'enctype' => null,
    'maxWidth' => 'max-w-4xl',
])

@include('partials.page-header', [
    'title' => $title,
    'description' => $description,
])

<section class="surface-card mt-6 {{ $maxWidth }} mx-auto p-5 lg:p-6" data-reveal>
    <form
        method="POST"
        action="{{ $action }}"
        @if ($enctype) enctype="{{ $enctype }}" @endif
        class="space-y-6"
    >
        @csrf
        @if (! in_array(strtoupper($method), ['POST', 'GET']))
            @method($method)
        @endif

        {{ $slot }}

        <div class="flex flex-wrap gap-3 border-t border-slate-200 pt-6">
            <button type="submit" class="btn-primary">{{ $submitLabel }}</button>
            <a href="{{ $cancelUrl }}" class="btn-secondary">Annuler</a>
        </div>
    </form>
</section>
