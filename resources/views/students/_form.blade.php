<div class="grid gap-6 md:grid-cols-2">
    <div>
        <label for="first_name" class="label">Prénom</label>
        <input id="first_name" name="first_name" type="text" required value="{{ old('first_name', $student->first_name ?? '') }}" class="field">
    </div>

    <div>
        <label for="last_name" class="label">Nom</label>
        <input id="last_name" name="last_name" type="text" required value="{{ old('last_name', $student->last_name ?? '') }}" class="field">
    </div>

    <div>
        <label for="birth_date" class="label">Date de naissance</label>
        <input id="birth_date" name="birth_date" type="date" required value="{{ old('birth_date', isset($student) ? $student->birth_date?->format('Y-m-d') : '') }}" class="field">
    </div>

    <div>
        <label for="classroom_id" class="label">Classe</label>
        <select id="classroom_id" name="classroom_id" required class="field">
            <option value="">Sélectionner</option>
            @foreach ($classrooms as $classroom)
                <option value="{{ $classroom->id }}" @selected(old('classroom_id', $student->classroom_id ?? '') == $classroom->id)>
                    {{ $classroom->name }} — {{ $classroom->level }}
                </option>
            @endforeach
        </select>
    </div>

    <div>
        <label for="school_year_id" class="label">Année scolaire</label>
        <select id="school_year_id" name="school_year_id" required class="field">
            <option value="">Sélectionner</option>
            @foreach ($schoolYears as $schoolYear)
                <option value="{{ $schoolYear->id }}" @selected(old('school_year_id', $selectedSchoolYearId ?? '') == $schoolYear->id)>
                    {{ $schoolYear->name }} — {{ $schoolYear->status?->label() }}
                </option>
            @endforeach
        </select>
    </div>

    <div class="md:col-span-2 border-t border-slate-200 pt-4">
        <label class="flex items-center gap-2 text-sm">
            <input type="checkbox" name="create_new_parent" value="1" @checked(old('create_new_parent')) data-toggle-parent>
            Créer un nouveau compte parent
        </label>
    </div>

    <div class="md:col-span-2" data-existing-parent>
        <label for="parent_id" class="label">Parent existant</label>
        <select id="parent_id" name="parent_id" class="field">
            <option value="">Sélectionner</option>
            @foreach ($parents as $parent)
                <option value="{{ $parent->id }}" @selected(old('parent_id', $student->parent_id ?? '') == $parent->id)>
                    {{ $parent->name }} — {{ $parent->email }}
                </option>
            @endforeach
        </select>
    </div>

    <div class="md:col-span-2 hidden grid gap-4 md:grid-cols-2" data-new-parent>
        <div>
            <label for="parent_name" class="label">Nom du parent</label>
            <input id="parent_name" name="parent_name" class="field" value="{{ old('parent_name') }}">
        </div>
        <div>
            <label for="parent_email" class="label">Email du parent</label>
            <input id="parent_email" name="parent_email" type="email" class="field" value="{{ old('parent_email') }}">
        </div>
        <div>
            <label for="parent_phone" class="label">Téléphone</label>
            <input id="parent_phone" name="parent_phone" class="field" value="{{ old('parent_phone') }}">
        </div>
        <div>
            <label for="parent_password" class="label">Mot de passe initial</label>
            <input id="parent_password" name="parent_password" type="password" class="field">
        </div>
    </div>
</div>

@push('scripts')
<script>
    const toggle = document.querySelector('[data-toggle-parent]');
    const existing = document.querySelector('[data-existing-parent]');
    const fresh = document.querySelector('[data-new-parent]');
    function sync() {
        const on = toggle?.checked;
        existing?.classList.toggle('hidden', on);
        fresh?.classList.toggle('hidden', !on);
    }
    toggle?.addEventListener('change', sync);
    sync();
</script>
@endpush
