<div class="grid gap-6 md:grid-cols-2">
    <div>
        <label for="level" class="label">{{ __('timetable.form_level') }}</label>
        <input list="level-options" id="level" name="level" type="text" required value="{{ old('level', $entry->level ?? '') }}" class="field">
        <datalist id="level-options">
            @foreach ($levels as $level)
                <option value="{{ $level }}"></option>
            @endforeach
        </datalist>
    </div>

    <div>
        <label for="section" class="label">{{ __('timetable.form_section') }}</label>
        <select id="section" name="section" required class="field">
            <option value="">{{ __('timetable.form_select') }}</option>
            @foreach ($sections as $value => $label)
                <option value="{{ $value }}" @selected(old('section', $entry->section?->value ?? '') === $value)>
                    {{ $label }}
                </option>
            @endforeach
        </select>
    </div>

    <div>
        <label for="subject" class="label">{{ __('timetable.form_subject') }}</label>
        <input id="subject" name="subject" type="text" required value="{{ old('subject', $entry->subject ?? '') }}" class="field">
    </div>

    <div>
        <label for="day" class="label">{{ __('timetable.form_day') }}</label>
        <select id="day" name="day" required class="field">
            <option value="">{{ __('timetable.form_select') }}</option>
            @foreach ($days as $value => $label)
                <option value="{{ $value }}" @selected(old('day', $entry->day?->value ?? '') === $value)>
                    {{ $label }}
                </option>
            @endforeach
        </select>
    </div>

    <div>
        <label for="start_time" class="label">{{ __('timetable.form_start_time') }}</label>
        <input id="start_time" name="start_time" type="time" required value="{{ old('start_time', isset($entry) ? substr($entry->start_time, 0, 5) : '') }}" class="field">
    </div>

    <div>
        <label for="end_time" class="label">{{ __('timetable.form_end_time') }}</label>
        <input id="end_time" name="end_time" type="time" required value="{{ old('end_time', isset($entry) ? substr($entry->end_time, 0, 5) : '') }}" class="field">
    </div>

    <div class="md:col-span-2">
        <label for="notes" class="label">{{ __('timetable.form_notes') }}</label>
        <textarea id="notes" name="notes" rows="4" class="field">{{ old('notes', $entry->notes ?? '') }}</textarea>
    </div>

    <div class="md:col-span-2 flex items-start gap-3 rounded-xl border border-slate-200 bg-slate-50 p-4">
        <input type="hidden" name="sync_courses" value="0">
        <input
            type="checkbox"
            id="sync_courses"
            name="sync_courses"
            value="1"
            class="mt-1"
            @checked(old('sync_courses', '1') !== '0')
        >
        <label for="sync_courses" class="text-sm text-slate-700">
            <span class="font-semibold text-slate-900">{{ __('timetable.form_sync') }}</span>
            <span class="mt-1 block text-slate-500">
                {{ __('timetable.form_sync_desc') }}
            </span>
        </label>
    </div>
</div>
