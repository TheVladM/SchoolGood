<div class="grid gap-6 md:grid-cols-2">
    <div>
        <label for="section" class="label">Section</label>
        <select id="section" name="section" required class="field" data-level-section>
            @foreach ($sections as $value => $label)
                <option value="{{ $value }}" @selected(old('section', $fee->section?->value ?? '') === $value)>{{ $label }}</option>
            @endforeach
        </select>
    </div>
    <div>
        <label for="level" class="label">Niveau</label>
        <select id="level" name="level" required class="field">
            @php $section = old('section', $fee->section?->value ?? 'francophone'); @endphp
            @foreach ($levelsBySection[$section] ?? [] as $value => $label)
                <option value="{{ $value }}" @selected(old('level', $fee->level ?? '') === $value)>{{ $label }}</option>
            @endforeach
        </select>
    </div>
    <div>
        <label for="registration_fee" class="label">Inscription (FCFA)</label>
        <input id="registration_fee" name="registration_fee" type="number" min="0" step="1" required class="field" value="{{ old('registration_fee', $fee->registration_fee ?? '') }}">
    </div>
    <div>
        <label for="first_installment" class="label">1ère tranche</label>
        <input id="first_installment" name="first_installment" type="number" min="0" step="1" required class="field" value="{{ old('first_installment', $fee->first_installment ?? '') }}">
    </div>
    <div>
        <label for="second_installment" class="label">2ème tranche</label>
        <input id="second_installment" name="second_installment" type="number" min="0" step="1" required class="field" value="{{ old('second_installment', $fee->second_installment ?? '') }}">
    </div>
    <div>
        <label for="third_installment" class="label">3ème tranche</label>
        <input id="third_installment" name="third_installment" type="number" min="0" step="1" required class="field" value="{{ old('third_installment', $fee->third_installment ?? '') }}">
    </div>
    <div class="md:col-span-2">
        <label for="notes" class="label">Notes</label>
        <textarea id="notes" name="notes" rows="3" class="field">{{ old('notes', $fee->notes ?? '') }}</textarea>
    </div>
</div>
