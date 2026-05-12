<div class="grid gap-6 md:grid-cols-2">
    <div>
        <label for="first_name" class="label">Prenom</label>
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
            <option value="">Selectionner</option>
            @foreach ($classrooms as $classroom)
                <option value="{{ $classroom->id }}" @selected(old('classroom_id', $student->classroom_id ?? '') == $classroom->id)>
                    {{ $classroom->name }} - {{ $classroom->level }}
                </option>
            @endforeach
        </select>
    </div>

    <div class="md:col-span-2">
        <label for="parent_id" class="label">Parent responsable</label>
        <select id="parent_id" name="parent_id" required class="field">
            <option value="">Selectionner</option>
            @foreach ($parents as $parent)
                <option value="{{ $parent->id }}" @selected(old('parent_id', $student->parent_id ?? '') == $parent->id)>
                    {{ $parent->name }} - {{ $parent->email }}
                </option>
            @endforeach
        </select>
    </div>
</div>
