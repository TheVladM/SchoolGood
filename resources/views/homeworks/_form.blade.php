<div class="space-y-6">
    <div>
        <label for="title" class="label">{{ __('homeworks.form_title') }}</label>
        <input
            type="text"
            id="title"
            name="title"
            value="{{ old('title', $homework?->title) }}"
            class="field @error('title') border-red-500 @enderror"
            placeholder="{{ __('homeworks.form_title_placeholder') }}"
            required
        >
        @error('title')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label for="description" class="label">{{ __('homeworks.form_description') }}</label>
        <textarea
            id="description"
            name="description"
            rows="4"
            class="field @error('description') border-red-500 @enderror"
            placeholder="{{ __('homeworks.form_desc_placeholder') }}"
        >{{ old('description', $homework?->description) }}</textarea>
        @error('description')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <div class="grid gap-6 md:grid-cols-2">
        <div>
            <label for="subject" class="label">{{ __('homeworks.form_subject') }}</label>
            <input
                type="text"
                id="subject"
                name="subject"
                value="{{ old('subject', $homework?->subject) }}"
                class="field @error('subject') border-red-500 @enderror"
                placeholder="{{ __('homeworks.form_subject_placeholder') }}"
            >
            @error('subject')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="teacher_id" class="label">{{ __('homeworks.form_teacher') }}</label>
            <select
                id="teacher_id"
                name="teacher_id"
                class="field @error('teacher_id') border-red-500 @enderror"
                required
            >
                <option value="">{{ __('homeworks.form_select') }}</option>
                @foreach ($teachers as $teacher)
                    @php
                        $selectedTeacherId = old('teacher_id', $homework?->teacher_id)
                            ?? (auth()->user()->hasRole(\App\Enums\UserRole::Teacher) ? auth()->id() : null);
                    @endphp
                    <option value="{{ $teacher->id }}" @selected($selectedTeacherId == $teacher->id)>
                        {{ $teacher->name }}
                    </option>
                @endforeach
            </select>
            @error('teacher_id')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="classroom_id" class="label">{{ __('homeworks.form_classroom') }}</label>
            <select
                id="classroom_id"
                name="classroom_id"
                class="field @error('classroom_id') border-red-500 @enderror"
                required
            >
                <option value="">{{ __('homeworks.form_select') }}</option>
                @foreach ($classrooms as $classroom)
                    <option value="{{ $classroom->id }}" @selected(old('classroom_id', $homework?->classroom_id) == $classroom->id)>
                        {{ $classroom->name }} ({{ $classroom->section?->label() }})
                    </option>
                @endforeach
            </select>
            @error('classroom_id')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="due_date" class="label">{{ __('homeworks.form_due_date') }}</label>
            <input
                type="datetime-local"
                id="due_date"
                name="due_date"
                value="{{ old('due_date', $homework?->due_date?->format('Y-m-d\TH:i')) }}"
                class="field @error('due_date') border-red-500 @enderror"
                required
            >
            @error('due_date')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>
    </div>
</div>
