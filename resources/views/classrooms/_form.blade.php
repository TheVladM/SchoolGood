<div class="grid gap-6 md:grid-cols-2">
    <div>
        <label for="name" class="label">Nom de la classe</label>
        <input id="name" name="name" type="text" required value="{{ old('name', $classroom->name ?? '') }}" class="field">
    </div>

    <div>
        <label for="section" class="label">Section</label>
        <select id="section" name="section" required class="field" data-classroom-section>
            <option value="">Sélectionner</option>
            @foreach ($sections as $value => $label)
                <option value="{{ $value }}" @selected(old('section', $classroom->section?->value ?? 'francophone') === $value)>
                    {{ $label }}
                </option>
            @endforeach
        </select>
    </div>

    <div>
        <label for="cycle_type" class="label">Type de cycle</label>
        <select id="cycle_type" name="cycle_type" required class="field">
            @foreach ($cycleTypes ?? [] as $value => $label)
                <option value="{{ $value }}" @selected(old('cycle_type', $classroom->cycle_type?->value ?? 'standard') === $value)>{{ $label }}</option>
            @endforeach
        </select>
    </div>

    <div>
        <label for="level" class="label">Niveau</label>
        <select id="level" name="level" required class="field">
            @php $sec = old('section', $classroom->section?->value ?? 'francophone'); @endphp
            @foreach (($levelsBySection[$sec] ?? []) as $value => $label)
                <option value="{{ $value }}" @selected(old('level', $classroom->level ?? '') === $value)>{{ $label }}</option>
            @endforeach
        </select>
    </div>

    <div>
        <label for="room_id" class="label">Salle (référentiel)</label>
        <select id="room_id" name="room_id" class="field">
            <option value="">— ou saisie libre —</option>
            @foreach ($rooms ?? [] as $room)
                <option value="{{ $room->id }}" @selected(old('room_id', $classroom->room_id ?? '') == $room->id)>{{ $room->displayLabel() }}</option>
            @endforeach
        </select>
    </div>

    <div>
        <label for="room" class="label">Nom de salle (affichage)</label>
        <input id="room" name="room" type="text" value="{{ old('room', $classroom->room ?? '') }}" class="field">
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

        // Pour le titulaire: montrer les enseignants avec la même langue que la section
        if (mainTeacherSelect) {
            Array.from(mainTeacherSelect.options).forEach((option, index) => {
                if (index === 0) return; // Skip "Aucun" option

                const teacherLanguage = option.dataset.language;
                let isVisible = true;

                // Vérifier la compatibilité langue/section pour le TITULAIRE
                if (selectedSection === 'francophone') {
                    isVisible = ['french', 'bilingual'].includes(teacherLanguage);
                } else if (selectedSection === 'anglophone') {
                    isVisible = ['english', 'bilingual'].includes(teacherLanguage);
                }

                option.style.display = isVisible ? 'block' : 'none';
            });
        }

        // Pour l'enseignant de langue: montrer les enseignants avec la LANGUE OPPOSÉE à la section
        if (languageTeacherSelect) {
            Array.from(languageTeacherSelect.options).forEach((option, index) => {
                if (index === 0) return; // Skip "Aucun" option

                const teacherLanguage = option.dataset.language;
                let isVisible = true;

                // Vérifier la compatibilité inverse pour l'ENSEIGNANT DE LANGUE
                // Si section = francophone, l'enseignant de langue doit être anglais/bilingue
                // Si section = anglophone, l'enseignant de langue doit être français/bilingue
                if (selectedSection === 'francophone') {
                    isVisible = ['english', 'bilingual'].includes(teacherLanguage);
                } else if (selectedSection === 'anglophone') {
                    isVisible = ['french', 'bilingual'].includes(teacherLanguage);
                }

                option.style.display = isVisible ? 'block' : 'none';
            });
        }
    }

    // Écouter les changements de section
    sectionSelect.addEventListener('change', updateTeacherOptions);
    
    // Initialiser au chargement
    updateTeacherOptions();
</script>
