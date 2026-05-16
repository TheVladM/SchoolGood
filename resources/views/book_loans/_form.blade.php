<div class="grid gap-6 md:grid-cols-2">
    <div class="md:col-span-2">
        <label for="book_id" class="label">Livre</label>
        <select id="book_id" name="book_id" required class="field">
            <option value="">Selectionner</option>
            @foreach ($books as $book)
                <option value="{{ $book->id }}" @selected(old('book_id', $loan->book_id ?? '') == $book->id)>
                    {{ $book->title }} - {{ $book->author }}
                </option>
            @endforeach
        </select>
    </div>

    <div>
        <label for="student_id" class="label">Eleve emprunteur</label>
        <select id="student_id" name="student_id" class="field">
            <option value="">Aucun</option>
            @foreach ($students as $student)
                <option value="{{ $student->id }}" @selected(old('student_id', $loan->student_id ?? '') == $student->id)>
                    {{ $student->full_name }} - {{ $student->classroom?->name }}
                </option>
            @endforeach
        </select>
    </div>

    <div>
        <label for="user_id" class="label">Enseignant emprunteur</label>
        <select id="user_id" name="user_id" class="field">
            <option value="">Aucun</option>
            @foreach ($teachers as $teacher)
                <option value="{{ $teacher->id }}" @selected(old('user_id', $loan->user_id ?? '') == $teacher->id)>
                    {{ $teacher->name }}
                </option>
            @endforeach
        </select>
    </div>

    <div>
        <label for="borrowed_at" class="label">Date d emprunt</label>
        <input id="borrowed_at" name="borrowed_at" type="date" required value="{{ old('borrowed_at', isset($loan) ? $loan->borrowed_at?->format('Y-m-d') : now()->format('Y-m-d')) }}" class="field">
    </div>

    <div>
        <label for="due_at" class="label">Date limite de retour</label>
        <input id="due_at" name="due_at" type="date" value="{{ old('due_at', isset($loan) ? $loan->due_at?->format('Y-m-d') : '') }}" class="field">
    </div>

    <div>
        <label for="daily_penalty_rate" class="label">Penalite par jour</label>
        <input id="daily_penalty_rate" name="daily_penalty_rate" type="number" min="0" step="0.01" value="{{ old('daily_penalty_rate', $loan->daily_penalty_rate ?? '') }}" class="field">
    </div>

    <div class="md:col-span-2">
        <label for="notes" class="label">Notes</label>
        <textarea id="notes" name="notes" rows="4" class="field">{{ old('notes', $loan->notes ?? '') }}</textarea>
    </div>
</div>
