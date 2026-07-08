<div class="grid gap-6 md:grid-cols-2">
    <div>
        <label for="name" class="label">{{ __('classrooms.form_name') }}</label>
        <input id="name" name="name" type="text" required value="{{ old('name', $classroom->name ?? '') }}" class="field">
    </div>

    <div>
        <label for="section" class="label">{{ __('classrooms.form_section') }}</label>
        <select id="section" name="section" required class="field" data-classroom-section>
            <option value="">{{ __('classrooms.form_select') }}</option>
            @foreach ($sections as $value => $label)
                <option value="{{ $value }}" @selected(old('section', $classroom->section?->value ?? 'francophone') === $value)>
                    {{ $label }}
                </option>
            @endforeach
        </select>
    </div>

    <div>
        <label for="cycle_type" class="label">{{ __('classrooms.form_cycle_type') }}</label>
        <select id="cycle_type" name="cycle_type" required class="field">
            @foreach ($cycleTypes ?? [] as $value => $label)
                <option value="{{ $value }}" @selected(old('cycle_type', $classroom->cycle_type?->value ?? 'standard') === $value)>{{ $label }}</option>
            @endforeach
        </select>
    </div>

    <div>
        <label for="level" class="label">{{ __('classrooms.form_level') }}</label>
        <select id="level" name="level" required class="field">
            @php $sec = old('section', $classroom->section?->value ?? 'francophone'); @endphp
            @foreach (($levelsBySection[$sec] ?? []) as $value => $label)
                <option value="{{ $value }}" @selected(old('level', $classroom->level ?? '') === $value)>{{ $label }}</option>
            @endforeach
        </select>
    </div>

    <div>
        <label for="room_id" class="label">{{ __('classrooms.form_room_ref') }}</label>
        <select id="room_id" name="room_id" class="field">
            <option value="">{{ __('classrooms.form_room_or_free') }}</option>
            @foreach ($rooms ?? [] as $room)
                <option value="{{ $room->id }}" @selected(old('room_id', $classroom->room_id ?? '') == $room->id)>{{ $room->displayLabel() }}</option>
            @endforeach
        </select>
    </div>

    <div>
        <label for="room" class="label">{{ __('classrooms.form_room_free') }}</label>
        <input id="room" name="room" type="text" value="{{ old('room', $classroom->room ?? '') }}" class="field">
    </div>

    <div>
        <label for="location" class="label">{{ __('classrooms.form_location') }}</label>
        <input id="location" name="location" type="text" value="{{ old('location', $classroom->location ?? '') }}" class="field">
    </div>

    <div>
        <label for="main_teacher_id" class="label">{{ __('classrooms.form_main_teacher') }}</label>
        <select id="main_teacher_id" name="main_teacher_id" class="field teacher-select">
            <option value="">{{ __('classrooms.form_none') }}</option>
            @foreach ($teachers as $teacher)
                <option
                    value="{{ $teacher->id }}"
                    data-language="{{ $teacher->teaches_language?->value ?? 'french' }}"
                    @selected(old('main_teacher_id', $classroom->main_teacher_id ?? '') == $teacher->id)
                >
                    {{ $teacher->name }}
                </option>
            @endforeach
        </select>
    </div>

    <div class="md:col-span-2">
        <label for="language_teacher_id" class="label">{{ __('classrooms.form_language_teacher') }}</label>
        <select id="language_teacher_id" name="language_teacher_id" class="field teacher-select">
            <option value="">{{ __('classrooms.form_none') }}</option>
            @foreach ($teachers as $teacher)
                <option
                    value="{{ $teacher->id }}"
                    data-language="{{ $teacher->teaches_language?->value ?? 'french' }}"
                    @selected(old('language_teacher_id', $classroom->language_teacher_id ?? '') == $teacher->id)
                >
                    {{ $teacher->name }}
                </option>
            @endforeach
        </select>
        <p class="mt-2 text-xs text-slate-500">{{ __('classrooms.form_language_hint') }}</p>
    </div>
</div>

<script>
    const sectionSelect = document.getElementById('section');
    const mainTeacherSelect = document.getElementById('main_teacher_id');
    const languageTeacherSelect = document.getElementById('language_teacher_id');

    function updateTeacherOptions() {
        const selectedSection = sectionSelect.value;

        if (mainTeacherSelect) {
            Array.from(mainTeacherSelect.options).forEach((option, index) => {
                if (index === 0) return;
                const teacherLanguage = option.dataset.language;
                let isVisible = true;
                if (selectedSection === 'francophone') {
                    isVisible = ['french', 'bilingual'].includes(teacherLanguage);
                } else if (selectedSection === 'anglophone') {
                    isVisible = ['english', 'bilingual'].includes(teacherLanguage);
                }
                option.style.display = isVisible ? 'block' : 'none';
            });
        }

        if (languageTeacherSelect) {
            Array.from(languageTeacherSelect.options).forEach((option, index) => {
                if (index === 0) return;
                const teacherLanguage = option.dataset.language;
                let isVisible = true;
                if (selectedSection === 'francophone') {
                    isVisible = ['english', 'bilingual'].includes(teacherLanguage);
                } else if (selectedSection === 'anglophone') {
                    isVisible = ['french', 'bilingual'].includes(teacherLanguage);
                }
                option.style.display = isVisible ? 'block' : 'none';
            });
        }
    }

    sectionSelect.addEventListener('change', updateTeacherOptions);
    updateTeacherOptions();
</script>
