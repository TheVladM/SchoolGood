<div class="grid gap-6 md:grid-cols-2">
    <div>
        <label for="name" class="label">Nom de la classe</label>
        <input id="name" name="name" type="text" required value="{{ old('name', $classroom->name ?? '') }}" class="field">
    </div>

    <div>
        <label for="level" class="label">Niveau</label>
        <input id="level" name="level" type="text" required value="{{ old('level', $classroom->level ?? '') }}" class="field">
        <p class="mt-2 text-xs text-slate-500">Exemples: CM1, CM2, SIL, Class 1, Nursery 2.</p>
    </div>

    <div>
        <label for="section" class="label">Section</label>
        <select id="section" name="section" required class="field">
            <option value="">Selectionner</option>
            @foreach ($sections as $value => $label)
                <option value="{{ $value }}" @selected(old('section', $classroom->section?->value ?? '') === $value)>
                    {{ $label }}
                </option>
            @endforeach
        </select>
    </div>

    <div>
        <label for="room" class="label">Salle</label>
        <input id="room" name="room" type="text" required value="{{ old('room', $classroom->room ?? '') }}" class="field">
    </div>

    <div>
        <label for="location" class="label">Localisation</label>
        <input id="location" name="location" type="text" value="{{ old('location', $classroom->location ?? '') }}" class="field">
    </div>

    <div>
        <label for="main_teacher_id" class="label">Enseignant titulaire</label>
        <select id="main_teacher_id" name="main_teacher_id" class="field teacher-select">
            <option value="">Aucun</option>
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
        <label for="language_teacher_id" class="label">Enseignant de langue</label>
        <select id="language_teacher_id" name="language_teacher_id" class="field teacher-select">
            <option value="">Aucun</option>
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
        <p class="mt-2 text-xs text-slate-500">
            Pour les classes primaires francophones ou anglophones, la plateforme demande deux enseignants differents.
        </p>
    </div>
</div>

<script>
    // Filtrer les enseignants disponibles selon la section sélectionnée
    const sectionSelect = document.getElementById('section');
    const mainTeacherSelect = document.getElementById('main_teacher_id');
    const languageTeacherSelect = document.getElementById('language_teacher_id');

    function updateTeacherOptions() {
        const selectedSection = sectionSelect.value;

        // Parcourir les options des enseignants
        [mainTeacherSelect, languageTeacherSelect].forEach(select => {
            Array.from(select.options).forEach((option, index) => {
                if (index === 0) return; // Skip "Aucun" option

                const teacherLanguage = option.dataset.language;
                let isVisible = true;

                // Vérifier la compatibilité langue/section
                if (selectedSection === 'francophone') {
                    isVisible = ['french', 'bilingual'].includes(teacherLanguage);
                } else if (selectedSection === 'anglophone') {
                    isVisible = ['english', 'bilingual'].includes(teacherLanguage);
                }

                // Masquer ou afficher l'option
                option.style.display = isVisible ? 'block' : 'none';
            });
        });
    }

    // Écouter les changements de section
    sectionSelect.addEventListener('change', updateTeacherOptions);
    
    // Initialiser au chargement
    updateTeacherOptions();

