<div class="grid gap-6 md:grid-cols-2">
    <div>
        <label for="name" class="label">Nom complet</label>
        <input
            id="name"
            name="name"
            type="text"
            required
            value="{{ old('name', $managedUser->name ?? '') }}"
            class="field"
        >
    </div>

    <div>
        <label for="email" class="label">Email</label>
        <input
            id="email"
            name="email"
            type="email"
            required
            value="{{ old('email', $managedUser->email ?? '') }}"
            class="field"
        >
    </div>

    <div>
        <label for="phone" class="label">Telephone</label>
        <input
            id="phone"
            name="phone"
            type="text"
            value="{{ old('phone', $managedUser->phone ?? '') }}"
            class="field"
        >
    </div>

    <div>
        <label for="role" class="label">Role</label>
        <select id="role" name="role" required class="field">
            <option value="">Selectionner</option>
            @foreach ($roles as $value => $label)
                <option value="{{ $value }}" @selected(old('role', $managedUser->role?->value ?? '') === $value)>
                    {{ $label }}
                </option>
            @endforeach
        </select>
    </div>

    <div>
        <label for="department" class="label">Service</label>
        <select id="department" name="department" class="field">
            <option value="">Selectionner</option>
            @foreach ($departments as $value => $label)
                <option value="{{ $value }}" @selected(old('department', $managedUser->department?->value ?? '') === $value)>
                    {{ $label }}
                </option>
            @endforeach
        </select>
    </div>

    <div>
        <label for="job_title" class="label">Fonction</label>
        <input
            id="job_title"
            name="job_title"
            type="text"
            value="{{ old('job_title', $managedUser->job_title ?? '') }}"
            class="field"
        >
    </div>

    <div>
        <label for="password" class="label">Mot de passe</label>
        <input id="password" name="password" type="password" class="field">
    </div>

    <div>
        <label for="password_confirmation" class="label">Confirmation du mot de passe</label>
        <input id="password_confirmation" name="password_confirmation" type="password" class="field">
    </div>
</div>
