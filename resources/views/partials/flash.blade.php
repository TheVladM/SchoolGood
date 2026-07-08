@if (session('success') || $errors->any())
    <div class="flash-stack">
        @if (session('success'))
            <div class="flash flash--success" data-flash role="status">
                <div class="flash__body">
                    <svg class="flash__icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
                    </svg>
                    <div>
                        <p class="flash__title">{{ __('ui.success') }}</p>
                        <p class="flash__text">{{ session('success') }}</p>
                    </div>
                </div>
                <button type="button" class="flash__close" data-flash-close aria-label="{{ __('ui.close') }}">&times;</button>
            </div>
        @endif

        @if ($errors->any())
            <div class="flash flash--error" data-flash role="alert">
                <div class="flash__body">
                    <svg class="flash__icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126ZM12 15.75h.007v.008H12v-.008Z"/>
                    </svg>
                    <div>
                        <p class="flash__title">{{ $errors->count() > 1 ? $errors->count().' '.__('ui.errors_plural') : __('ui.error') }}</p>
                        <div class="flash__text">
                            @foreach ($errors->all() as $error)
                                <p>{{ $error }}</p>
                            @endforeach
                        </div>
                    </div>
                </div>
                <button type="button" class="flash__close" data-flash-close aria-label="{{ __('ui.close') }}">&times;</button>
            </div>
        @endif
    </div>
@endif
