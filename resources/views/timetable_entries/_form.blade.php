<div class="grid gap-6 md:grid-cols-2">
    <div>
        <label for="level" class="label">Niveau</label>
        <input list="level-options" id="level" name="level" type="text" required value="{{ old('level', $entry->level ?? '') }}" class="field">
        <datalist id="level-options">
            @foreach ($levels as $level)
                <option value="{{ $level }}"></option>
            @endforeach
        </datalist>
    </div>

    <div>
        <label for="section" class="label">Section</label>
        <select id="section" name="section" required class="field">
            <option value="">Selectionner</option>
            @foreach ($sections as $value => $label)
                <option value="{{ $value }}" @selected(old('section', $entry->section?->value ?? '') === $value)>
                    {{ $label }}
                </option>
            @endforeach
        </select>
    </div>

    <div>
        <label for="subject" class="label">Matiere</label>
        <input id="subject" name="subject" type="text" required value="{{ old('subject', $entry->subject ?? '') }}" class="field">
    </div>

    <div>
        <label for="day" class="label">Jour</label>
        <select id="day" name="day" required class="field">
            <option value="">Selectionner</option>
            @foreach ($days as $value => $label)
                <option value="{{ $value }}" @selected(old('day', $entry->day?->value ?? '') === $value)>
                    {{ $label }}
                </option>
            @endforeach
        </select>
    </div>

    <div>
        <label for="start_time" class="label">Heure de debut</label>
        <input id="start_time" name="start_time" type="time" required value="{{ old('start_time', isset($entry) ? substr($entry->start_time, 0, 5) : '') }}" class="field">
    </div>

    <div>
        <label for="end_time" class="label">Heure de fin</label>
        <input id="end_time" name="end_time" type="time" required value="{{ old('end_time', isset($entry) ? substr($entry->end_time, 0, 5) : '') }}" class="field">
    </div>

    <div class="md:col-span-2">
        <label for="notes" class="label">Notes</label>
        <textarea id="notes" name="notes" rows="4" class="field">{{ old('notes', $entry->notes ?? '') }}</textarea>
    </div>
</div>
