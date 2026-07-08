@extends('layouts.app')

@section('title', __('ui.my_profile') . ' | SchoolGood')
@section('topbar_title', __('ui.my_profile'))

@section('content')
<div class="form-card max-w-2xl mx-auto mt-6" data-reveal>

    {{-- Header --}}
    <div class="form-card__header">
        <h1 class="form-card__title">{{ __('ui.my_profile') }}</h1>
        <p class="form-card__desc">{{ __('ui.profile_desc') }}</p>
    </div>

    <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="form-card__body space-y-6">

            {{-- ── Photo de profil ── --}}
            <div class="profile-photo-row">
                <div class="profile-photo-wrap">
                    <div class="profile-photo-frame">
                        <img id="photo-preview"
                             src="{{ $user->photoURL ?? '' }}"
                             alt="{{ $user->name }}"
                             style="{{ $user->photoURL ? '' : 'display:none' }}">
                        <svg id="photo-placeholder"
                             class="profile-photo-frame__icon"
                             style="{{ $user->photoURL ? 'display:none' : '' }}"
                             fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                  d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z"/>
                        </svg>
                    </div>
                    {{-- Bouton caméra --}}
                    <label for="photo" class="profile-photo-cam" title="{{ __('ui.choose_photo') }}">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M6.827 6.175A2.31 2.31 0 0 1 9.033 4.5h5.934a2.31 2.31 0 0 1 2.206 1.675l.028.093A2.31 2.31 0 0 0 19.5 8.578V9a2.25 2.25 0 0 1-2.25 2.25H6.75A2.25 2.25 0 0 1 4.5 9v-.422a2.31 2.31 0 0 0 2.299-2.31l.028-.093ZM12 16.5a3 3 0 1 0 0-6 3 3 0 0 0 0 6Z"/>
                        </svg>
                    </label>
                </div>

                <div class="profile-photo-info">
                    <label class="label">{{ __('ui.profile_photo') }}</label>
                    <div class="profile-photo-actions">
                        <label for="photo" class="btn-secondary" style="cursor:pointer; font-size:0.82rem; min-height:2rem; padding:0 0.85rem;">
                            {{ __('ui.choose_photo') }}
                        </label>
                        @if($user->photoURL)
                        <button type="button" id="remove-photo-btn"
                                class="btn-danger" style="font-size:0.82rem; min-height:2rem; padding:0 0.85rem;">
                            {{ __('ui.remove_photo') }}
                        </button>
                        @endif
                    </div>
                    <p class="profile-photo-hint">{{ __('ui.photo_hint') }}</p>
                    @error('photo')<p class="form-field__error">{{ $message }}</p>@enderror
                    <input id="photo" name="photo" type="file"
                           accept="image/jpeg,image/png,image/jpg,image/webp"
                           style="position:absolute;width:1px;height:1px;overflow:hidden;clip:rect(0,0,0,0);">
                    <input type="hidden" name="remove_photo" id="remove-photo-input" value="0">
                </div>
            </div>

            <hr style="border:none;border-top:1px solid var(--sg-border);">

            {{-- ── Informations personnelles ── --}}
            <div>
                <span class="section-heading">{{ __('ui.personal_info') }}</span>
                <div class="space-y-5">
                    <div>
                        <label for="name" class="label">{{ __('ui.full_name') }}</label>
                        <div class="field-wrap">
                            <svg class="field-wrap__icon" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75"
                                      d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z"/>
                            </svg>
                            <input id="name" name="name" type="text" required class="field"
                                   value="{{ old('name', $user->name) }}"
                                   placeholder="Votre nom complet">
                        </div>
                        @error('name')<p class="form-field__error">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label for="email" class="label">{{ __('ui.email') }}</label>
                        <div class="field-wrap">
                            <svg class="field-wrap__icon" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75"
                                      d="M21.75 6.75v10.5a2.25 2.25 0 0 1-2.25 2.25h-15a2.25 2.25 0 0 1-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25m19.5 0v.243a2.25 2.25 0 0 1-1.07 1.916l-7.5 4.615a2.25 2.25 0 0 1-2.36 0L3.32 8.91a2.25 2.25 0 0 1-1.07-1.916V6.75"/>
                            </svg>
                            <input id="email" name="email" type="email" required class="field"
                                   value="{{ old('email', $user->email) }}"
                                   placeholder="votre@email.com">
                        </div>
                        @error('email')<p class="form-field__error">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label for="phone" class="label">{{ __('ui.phone') }}</label>
                        <div class="field-wrap">
                            <svg class="field-wrap__icon" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75"
                                      d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 0 0 2.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-.282.376-.769.542-1.21.38a12.035 12.035 0 0 1-7.143-7.143c-.162-.441.004-.928.38-1.21l1.293-.97c.363-.271.527-.734.417-1.173L6.963 3.102a1.125 1.125 0 0 0-1.091-.852H4.5A2.25 2.25 0 0 0 2.25 4.5v2.25Z"/>
                            </svg>
                            <input id="phone" name="phone" type="tel" class="field"
                                   value="{{ old('phone', $user->phone) }}"
                                   placeholder="ex: +237 6XX XXX XXX">
                        </div>
                        @error('phone')<p class="form-field__error">{{ $message }}</p>@enderror
                    </div>
                </div>
            </div>

            <hr style="border:none;border-top:1px solid var(--sg-border);">

            {{-- ── Mot de passe ── --}}
            <div>
                <span class="section-heading">{{ __('ui.change_password') }}</span>
                <p style="margin:-0.25rem 0 1.25rem; font-size:0.82rem; color:var(--sg-muted);">
                    {{ __('ui.password_hint') }}
                </p>
                <div class="space-y-5">
                    <div>
                        <label for="current_password" class="label">{{ __('ui.current_password') }}</label>
                        <div class="field-wrap">
                            <svg class="field-wrap__icon" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75"
                                      d="M16.5 10.5V6.75a4.5 4.5 0 1 0-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 0 0 2.25-2.25v-6.75a2.25 2.25 0 0 0-2.25-2.25H6.75a2.25 2.25 0 0 0-2.25 2.25v6.75a2.25 2.25 0 0 0 2.25 2.25Z"/>
                            </svg>
                            <input id="current_password" name="current_password" type="password"
                                   class="field field--end" autocomplete="current-password"
                                   placeholder="••••••••">
                            <button type="button" class="field-wrap__toggle" data-toggle="current_password" aria-label="Afficher">
                                <svg data-eye fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75"
                                          d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/>
                                </svg>
                            </button>
                        </div>
                        @error('current_password')<p class="form-field__error">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label for="password" class="label">{{ __('ui.new_password') }}</label>
                        <div class="field-wrap">
                            <svg class="field-wrap__icon" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75"
                                      d="M16.5 10.5V6.75a4.5 4.5 0 1 0-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 0 0 2.25-2.25v-6.75a2.25 2.25 0 0 0-2.25-2.25H6.75a2.25 2.25 0 0 0-2.25 2.25v6.75a2.25 2.25 0 0 0 2.25 2.25Z"/>
                            </svg>
                            <input id="password" name="password" type="password"
                                   class="field field--end" autocomplete="new-password"
                                   placeholder="••••••••">
                            <button type="button" class="field-wrap__toggle" data-toggle="password" aria-label="Afficher">
                                <svg data-eye fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75"
                                          d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/>
                                </svg>
                            </button>
                        </div>
                        @error('password')<p class="form-field__error">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label for="password_confirmation" class="label">{{ __('ui.confirm_password') }}</label>
                        <div class="field-wrap">
                            <svg class="field-wrap__icon" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75"
                                      d="M16.5 10.5V6.75a4.5 4.5 0 1 0-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 0 0 2.25-2.25v-6.75a2.25 2.25 0 0 0-2.25-2.25H6.75a2.25 2.25 0 0 0-2.25 2.25v6.75a2.25 2.25 0 0 0 2.25 2.25Z"/>
                            </svg>
                            <input id="password_confirmation" name="password_confirmation" type="password"
                                   class="field field--end" autocomplete="new-password"
                                   placeholder="••••••••">
                            <button type="button" class="field-wrap__toggle" data-toggle="password_confirmation" aria-label="Afficher">
                                <svg data-eye fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75"
                                          d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/>
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <div class="form-card__footer">
            <button type="submit" class="btn-primary">{{ __('ui.save_changes') }}</button>
            <a href="{{ route('dashboard') }}" class="btn-secondary">{{ __('ui.cancel') }}</a>
        </div>
    </form>
</div>

@push('scripts')
<script>
(function () {
    /* ── Photo preview ── */
    const input       = document.getElementById('photo');
    const preview     = document.getElementById('photo-preview');
    const placeholder = document.getElementById('photo-placeholder');
    const removeBtn   = document.getElementById('remove-photo-btn');
    const removeInput = document.getElementById('remove-photo-input');

    if (input) {
        input.addEventListener('change', function () {
            const file = this.files[0];
            if (!file) return;
            const reader = new FileReader();
            reader.onload = function (e) {
                preview.src = e.target.result;
                preview.style.display = '';
                placeholder.style.display = 'none';
                removeInput.value = '0';
            };
            reader.readAsDataURL(file);
        });
    }

    if (removeBtn) {
        removeBtn.addEventListener('click', function () {
            preview.src = '';
            preview.style.display = 'none';
            placeholder.style.display = '';
            input.value = '';
            removeInput.value = '1';
        });
    }

    /* ── Password toggles ── */
    document.querySelectorAll('[data-toggle]').forEach(function (btn) {
        btn.addEventListener('click', function () {
            const fieldId = this.dataset.toggle;
            const field   = document.getElementById(fieldId);
            if (!field) return;
            const isHidden = field.type === 'password';
            field.type = isHidden ? 'text' : 'password';
            const eye = this.querySelector('[data-eye]');
            if (eye) {
                eye.innerHTML = isHidden
                    ? '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M3.98 8.223A10.477 10.477 0 0 0 1.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.451 10.451 0 0 1 12 4.5c4.756 0 8.773 3.162 10.065 7.498a10.522 10.522 0 0 1-4.293 5.774M6.228 6.228 3 3m3.228 3.228 3.65 3.65m7.894 7.894L21 21m-3.228-3.228-3.65-3.65m0 0a3 3 0 1 0-4.243-4.243m4.242 4.242L9.88 9.88"/>'
                    : '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/>';
            }
        });
    });
})();
</script>
@endpush
@endsection
