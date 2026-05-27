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
        <label for="department" class="label" id="department_label">Service</label>
        <select id="department" name="department" class="field transition-all" disabled>
            <option value="">Selectionner</option>
            @foreach ($departments as $value => $label)
                <option value="{{ $value }}" @selected(old('department', $managedUser->department?->value ?? '') === $value)>
                    {{ $label }}
                </option>
            @endforeach
        </select>
    </div>

    <div>
        <label for="teaches_language" class="label" id="teaches_language_label">Langue d'enseignement</label>
        <select id="teaches_language" name="teaches_language" class="field transition-all">
            <option value="">Selectionner</option>
            <option value="french" @selected(old('teaches_language', $managedUser->teaches_language?->value ?? '') === 'french')>Français</option>
            <option value="english" @selected(old('teaches_language', $managedUser->teaches_language?->value ?? '') === 'english')>Anglais</option>
            <option value="bilingual" @selected(old('teaches_language', $managedUser->teaches_language?->value ?? '') === 'bilingual')>Bilingue</option>
        </select>
    </div>

    <div>
        <label for="job_title" class="label" id="job_title_label">Fonction</label>
        <input
            id="job_title"
            name="job_title"
            type="text"
            value="{{ old('job_title', $managedUser->job_title ?? '') }}"
            class="field transition-all"
            disabled
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

<style>
    /* Styles pour les champs disabled */
    .field:disabled {
        background-color: #f3f4f6;
        color: #9ca3af;
        border-color: #e5e7eb;
        cursor: not-allowed;
        opacity: 0.65;
    }

    .field:disabled::placeholder {
        color: #d1d5db;
    }

    .field:disabled:hover {
        border-color: #e5e7eb;
    }

    /* Label pour les champs disabled */
    label {
        transition: color 0.2s ease;
    }

    .label.disabled-label {
        color: #9ca3af;
    }

    /* Cacher le champ langue d'enseignement par défaut */
    #teaches_language {
        display: none;
    }

    #teaches_language_label {
        display: none;
    }

    /* Afficher quand le rôle est enseignant */
    #teaches_language.show {
        display: block;
    }

    #teaches_language_label.show {
        display: block;
    }
</style>

<script>
    // Gérer l'activation/désactivation des champs Service, Fonction et Langue
    document.addEventListener('DOMContentLoaded', function() {
        const roleSelect = document.getElementById('role');
        const departmentSelect = document.getElementById('department');
        const jobTitleInput = document.getElementById('job_title');
        const teachesLanguageSelect = document.getElementById('teaches_language');
        const teachesLanguageDiv = teachesLanguageSelect?.parentElement;
        const departmentLabel = document.getElementById('department_label');
        const jobTitleLabel = document.getElementById('job_title_label');
        const teachesLanguageLabel = document.getElementById('teaches_language_label');

        function updateFieldsState() {
            const selectedRole = roleSelect.value;
            const isParent = selectedRole === 'parent';
            const isTeacher = selectedRole === 'teacher';

            // Gérer la visibilité du champ langue d'enseignement (seulement pour les enseignants)
            teachesLanguageSelect.classList.toggle('show', isTeacher);
            teachesLanguageLabel.classList.toggle('show', isTeacher);

            // Désactiver si Parent, activer sinon
            departmentSelect.disabled = isParent;
            jobTitleInput.disabled = isParent;

            // Ajouter/Retirer la classe disabled-label
            departmentLabel.classList.toggle('disabled-label', isParent);
            jobTitleLabel.classList.toggle('disabled-label', isParent);

            // Vider les champs si Parent et les désactiver
            if (isParent) {
                departmentSelect.value = '';
                jobTitleInput.value = '';
            }

            // Si ce n'est pas un enseignant, vider le champ langue
            if (!isTeacher) {
                teachesLanguageSelect.value = '';
            }
        }

        // Écouter les changements du rôle
        roleSelect.addEventListener('change', updateFieldsState);
        
        // Initialiser l'état au chargement
        updateFieldsState();
    });
</script>
