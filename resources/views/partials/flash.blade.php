@if (session('success') || $errors->any())
    <div class="flash-stack">
        @if (session('success'))
            <div class="flash flash--success" data-flash role="status">
                <div>
                    <p class="flash__title">Succes</p>
                    <p class="flash__text">{{ session('success') }}</p>
                </div>
                <button type="button" class="flash__close" data-flash-close aria-label="Fermer">&times;</button>
            </div>
        @endif

        @if ($errors->any())
            <div class="flash flash--error" data-flash role="alert">
                <div>
                    <p class="flash__title">Erreur</p>
                    <div class="flash__text">
                        @foreach ($errors->all() as $error)
                            <p>{{ $error }}</p>
                        @endforeach
                    </div>
                </div>
                <button type="button" class="flash__close" data-flash-close aria-label="Fermer">&times;</button>
            </div>
        @endif
    </div>
@endif
