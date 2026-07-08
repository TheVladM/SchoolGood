<div class="grid gap-6 md:grid-cols-2">
    <div class="md:col-span-2">
        <label for="title" class="label">{{ __('announcements.form_title') }}</label>
        <input id="title" name="title" type="text" required value="{{ old('title', $announcement->title ?? '') }}" class="field">
    </div>

    <div>
        <label for="audience" class="label">{{ __('announcements.form_audience') }}</label>
        <select id="audience" name="audience" required class="field">
            <option value="">{{ __('announcements.form_select') }}</option>
            @foreach ($audiences as $value => $label)
                <option value="{{ $value }}" @selected(old('audience', $announcement->audience?->value ?? '') === $value)>
                    {{ $label }}
                </option>
            @endforeach
        </select>
    </div>

    <div id="classroom-field">
        <label for="classroom_id" class="label">{{ __('announcements.form_classroom') }}</label>
        <select id="classroom_id" name="classroom_id" class="field">
            <option value="">{{ __('announcements.form_no_class') }}</option>
            @foreach ($classrooms as $classroom)
                <option value="{{ $classroom->id }}" @selected(old('classroom_id', $announcement->classroom_id ?? '') == $classroom->id)>
                    {{ $classroom->name }} - {{ $classroom->level }}
                </option>
            @endforeach
        </select>
    </div>

    <div id="parent-field" class="md:col-span-2">
        <label for="parent_id" class="label">{{ __('announcements.form_parent') }}</label>
        <select id="parent_id" name="parent_id" class="field">
            <option value="">{{ __('announcements.form_select_parent') }}</option>
            @foreach ($parents as $parent)
                <option value="{{ $parent->id }}" @selected(old('parent_id', $announcement->parent_id ?? '') == $parent->id)>
                    {{ $parent->name }} ({{ $parent->email }})
                </option>
            @endforeach
        </select>
    </div>

    <div class="md:col-span-2">
        <label for="content" class="label">{{ __('announcements.form_content') }}</label>
        <textarea id="content" name="content" rows="6" required class="field">{{ old('content', $announcement->content ?? '') }}</textarea>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const audience = document.getElementById('audience');
        const classroomField = document.getElementById('classroom-field');
        const parentField = document.getElementById('parent-field');
        const classroomSelect = document.getElementById('classroom_id');
        const parentSelect = document.getElementById('parent_id');

        function syncAudienceFields() {
            const value = audience.value;
            const isClassroom = value === 'classroom';
            const isParent = value === 'parent';

            classroomField.hidden = !isClassroom;
            parentField.hidden = !isParent;
            classroomSelect.disabled = !isClassroom;
            parentSelect.disabled = !isParent;

            if (!isClassroom) {
                classroomSelect.value = '';
            }

            if (!isParent) {
                parentSelect.value = '';
            }
        }

        audience.addEventListener('change', syncAudienceFields);
        syncAudienceFields();
    });
</script>
