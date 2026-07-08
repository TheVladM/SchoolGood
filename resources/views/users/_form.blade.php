<div class="grid gap-6 md:grid-cols-2">
    <div>
        <label for="name" class="label">{{ __('users.form_name') }}</label>
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
        <label for="email" class="label">{{ __('users.form_email') }}</label>
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
        <label for="phone" class="label">{{ __('users.form_phone') }}</label>
        <input
            id="phone"
            name="phone"
            type="text"
            value="{{ old('phone', $managedUser->phone ?? '') }}"
            class="field"
        >
    </div>

    <div>
        <label for="role" class="label">{{ __('users.form_role') }}</label>
        <select id="role" name="role" required class="field">
            <option value="">{{ __('users.form_select') }}</option>
            @foreach ($roles as $value => $label)
                <option value="{{ $value }}" @selected(old('role', $managedUser->role?->value ?? '') === $value)>
                    {{ $label }}
                </option>
            @endforeach
        </select>
    </div>

    <div>
        <label for="department" class="label" id="department_label">{{ __('users.form_department') }}</label>
        <select id="department" name="department" class="field transition-all" disabled>
            <option value="">{{ __('users.form_select') }}</option>
            @foreach ($departments as $value => $label)
                <option value="{{ $value }}" @selected(old('department', $managedUser->department?->value ?? '') === $value)>
                    {{ $label }}
                </option>
            @endforeach
        </select>
    </div>

    <div>
        <label for="teaches_language" class="label" id="teaches_language_label">{{ __('users.form_teaches_lang') }}</label>
        <select id="teaches_language" name="teaches_language" class="field transition-all">
            <option value="">{{ __('users.form_select') }}</option>
            <option value="french" @selected(old('teaches_language', $managedUser->teaches_language?->value ?? '') === 'french')>Français</option>
            <option value="english" @selected(old('teaches_language', $managedUser->teaches_language?->value ?? '') === 'english')>Anglais</option>
            <option value="bilingual" @selected(old('teaches_language', $managedUser->teaches_language?->value ?? '') === 'bilingual')>Bilingue</option>
        </select>
    </div>

    <div>
        <label for="job_title" class="label" id="job_title_label">{{ __('users.form_job_title') }}</label>
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
        <label for="password" class="label">{{ __('users.form_password') }}</label>
        <input id="password" name="password" type="password" class="field">
    </div>

    <div>
        <label for="password_confirmation" class="label">{{ __('users.form_password_conf') }}</label>
        <input id="password_confirmation" name="password_confirmation" type="password" class="field">
    </div>
</div>

<style>
    .field:disabled {
        background-color: #f3f4f6;
        color: #9ca3af;
        border-color: #e5e7eb;
        cursor: not-allowed;
        opacity: 0.65;
    }
    label { transition: color 0.2s ease; }
    .label.disabled-label { color: #9ca3af; }
    #teaches_language { display: none; }
    #teaches_language_label { display: none; }
    #teaches_language.show { display: block; }
    #teaches_language_label.show { display: block; }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const roleSelect = document.getElementById('role');
        const departmentSelect = document.getElementById('department');
        const jobTitleInput = document.getElementById('job_title');
        const teachesLanguageSelect = document.getElementById('teaches_language');
        const departmentLabel = document.getElementById('department_label');
        const jobTitleLabel = document.getElementById('job_title_label');
        const teachesLanguageLabel = document.getElementById('teaches_language_label');

        function updateFieldsState() {
            const selectedRole = roleSelect.value;
            const isParent = selectedRole === 'parent';
            const isTeacher = selectedRole === 'teacher';

            teachesLanguageSelect.classList.toggle('show', isTeacher);
            teachesLanguageLabel.classList.toggle('show', isTeacher);
            departmentSelect.disabled = isParent;
            jobTitleInput.disabled = isParent;
            departmentLabel.classList.toggle('disabled-label', isParent);
            jobTitleLabel.classList.toggle('disabled-label', isParent);

            if (isParent) {
                departmentSelect.value = '';
                jobTitleInput.value = '';
            }
            if (!isTeacher) {
                teachesLanguageSelect.value = '';
            }
        }

        roleSelect.addEventListener('change', updateFieldsState);
        updateFieldsState();
    });
</script>
