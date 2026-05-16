<div class="grid gap-6 md:grid-cols-2">
    <div>
        <label for="name" class="label">Nom de l annee scolaire</label>
        <input id="name" name="name" type="text" required value="{{ old('name', $schoolYear->name ?? '') }}" class="field">
    </div>

    <div>
        <label for="status" class="label">Statut</label>
        <select id="status" name="status" required class="field">
            <option value="">Selectionner</option>
            @foreach ($statuses as $value => $label)
                <option value="{{ $value }}" @selected(old('status', $schoolYear->status?->value ?? '') === $value)>
                    {{ $label }}
                </option>
            @endforeach
        </select>
    </div>

    <div>
        <label for="starts_on" class="label">Date de debut</label>
        <input id="starts_on" name="starts_on" type="date" required value="{{ old('starts_on', isset($schoolYear) ? $schoolYear->starts_on?->format('Y-m-d') : '') }}" class="field">
    </div>

    <div>
        <label for="ends_on" class="label">Date de fin</label>
        <input id="ends_on" name="ends_on" type="date" required value="{{ old('ends_on', isset($schoolYear) ? $schoolYear->ends_on?->format('Y-m-d') : '') }}" class="field">
    </div>

    <div>
        <label for="diploma_awarded_on" class="label">Remise des diplomes</label>
        <input id="diploma_awarded_on" name="diploma_awarded_on" type="date" value="{{ old('diploma_awarded_on', isset($schoolYear) ? $schoolYear->diploma_awarded_on?->format('Y-m-d') : '') }}" class="field">
    </div>

    <div>
        <label for="promotion_opens_on" class="label">Date de preparation des promotions</label>
        <input id="promotion_opens_on" name="promotion_opens_on" type="date" value="{{ old('promotion_opens_on', isset($schoolYear) ? $schoolYear->promotion_opens_on?->format('Y-m-d') : '') }}" class="field">
    </div>

    <div class="md:col-span-2">
        <label for="next_school_year_id" class="label">Annee suivante</label>
        <select id="next_school_year_id" name="next_school_year_id" class="field">
            <option value="">Aucune</option>
            @foreach ($availableNextYears as $year)
                <option value="{{ $year->id }}" @selected(old('next_school_year_id', $schoolYear->next_school_year_id ?? '') == $year->id)>
                    {{ $year->name }}
                </option>
            @endforeach
        </select>
    </div>
</div>
