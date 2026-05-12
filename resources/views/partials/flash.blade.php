@if (session('success') || $errors->any())
    <div class="flash-stack">
        @if (session('success'))
            <div class="flash flash--success" data-flash>
                <div>
                    <p class="flash__title">Operation reussie</p>
                    <p class="flash__text">{{ session('success') }}</p>
                </div>

                <button type="button" class="flash__close" data-flash-close aria-label="Fermer">x</button>
            </div>
        @endif

        @if ($errors->any())
            <div class="flash flash--error" data-flash>
                <div>
                    <p class="flash__title">Verification necessaire</p>
                    <div class="flash__text space-y-1">
                        @foreach ($errors->all() as $error)
                            <p>{{ $error }}</p>
                        @endforeach
                    </div>
                </div>

                <button type="button" class="flash__close" data-flash-close aria-label="Fermer">x</button>
            </div>
        @endif
    </div>
@endif
