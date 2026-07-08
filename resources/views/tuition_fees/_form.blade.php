<div class="grid gap-6 md:grid-cols-2">
    <div>
        <label for="section" class="label">{{ __('tuition_fees.form_section') }}</label>
        <select id="section" name="section" required class="field" data-level-section>
            @foreach ($sections as $value => $label)
                <option value="{{ $value }}" @selected(old('section', $fee->section?->value ?? '') === $value)>{{ $label }}</option>
            @endforeach
        </select>
    </div>
    <div>
        <label for="level" class="label">{{ __('tuition_fees.form_level') }}</label>
        <select id="level" name="level" required class="field">
            @php $section = old('section', $fee->section?->value ?? 'francophone'); @endphp
            @foreach ($levelsBySection[$section] ?? [] as $value => $label)
                <option value="{{ $value }}" @selected(old('level', $fee->level ?? '') === $value)>{{ $label }}</option>
            @endforeach
        </select>
    </div>
    <div>
        <label for="registration_fee" class="label">{{ __('tuition_fees.form_reg_fee') }}</label>
        <input id="registration_fee" name="registration_fee" type="number" min="0" step="1" required class="field" value="{{ old('registration_fee', $fee->registration_fee ?? '') }}">
    </div>
    <div>
        <label for="first_installment" class="label">{{ __('tuition_fees.form_first') }}</label>
        <input id="first_installment" name="first_installment" type="number" min="0" step="1" required class="field" value="{{ old('first_installment', $fee->first_installment ?? '') }}">
    </div>
    <div>
        <label for="second_installment" class="label">{{ __('tuition_fees.form_second') }}</label>
        <input id="second_installment" name="second_installment" type="number" min="0" step="1" required class="field" value="{{ old('second_installment', $fee->second_installment ?? '') }}">
    </div>
    <div>
        <label for="third_installment" class="label">{{ __('tuition_fees.form_third') }}</label>
        <input id="third_installment" name="third_installment" type="number" min="0" step="1" required class="field" value="{{ old('third_installment', $fee->third_installment ?? '') }}">
    </div>
    <div class="md:col-span-2">
        <label for="notes" class="label">{{ __('tuition_fees.form_notes') }}</label>
        <textarea id="notes" name="notes" rows="3" class="field">{{ old('notes', $fee->notes ?? '') }}</textarea>
    </div>
</div>
