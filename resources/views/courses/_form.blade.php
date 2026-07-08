<div class="grid gap-6 md:grid-cols-2">
    <div class="md:col-span-2">
        <label for="title" class="label">{{ __('courses.form_title') }}</label>
        <input id="title" name="title" type="text" required value="{{ old('title', $course->title ?? '') }}" class="field">
    </div>

    <div class="md:col-span-2">
        <label for="content" class="label">{{ __('courses.form_content') }}</label>
        <textarea id="content" name="content" rows="5" required class="field">{{ old('content', $course->content ?? '') }}</textarea>
    </div>

    <div>
        <label for="teacher_id" class="label">{{ __('courses.form_teacher') }}</label>
        <select id="teacher_id" name="teacher_id" required class="field" @if(auth()->user()->hasRole(\App\Enums\UserRole::Teacher)) disabled @endif>
            <option value="">{{ __('courses.form_select') }}</option>
            @foreach ($teachers as $teacher)
                <option value="{{ $teacher->id }}" @selected(old('teacher_id', $course->teacher_id ?? auth()->id()) == $teacher->id)>
                    {{ $teacher->name }}
                </option>
            @endforeach
        </select>

        @if(auth()->user()->hasRole(\App\Enums\UserRole::Teacher))
            <input type="hidden" name="teacher_id" value="{{ old('teacher_id', $course->teacher_id ?? auth()->id()) }}">
        @endif
    </div>

    <div>
        <label for="classroom_id" class="label">{{ __('courses.form_classroom') }}</label>
        <select id="classroom_id" name="classroom_id" required class="field">
            <option value="">{{ __('courses.form_select') }}</option>
            @foreach ($classrooms as $classroom)
                <option value="{{ $classroom->id }}" @selected(old('classroom_id', $course->classroom_id ?? '') == $classroom->id)>
                    {{ $classroom->name }}
                </option>
            @endforeach
        </select>
    </div>

    <div>
        <label for="day" class="label">{{ __('courses.form_day') }}</label>
        <select id="day" name="day" required class="field">
            <option value="">{{ __('courses.form_select') }}</option>
            @foreach ($days as $value => $label)
                <option value="{{ $value }}" @selected(old('day', $course->day?->value ?? '') === $value)>
                    {{ $label }}
                </option>
            @endforeach
        </select>
    </div>
</div>
