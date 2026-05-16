<div class="grid gap-6 md:grid-cols-2">
    <div class="md:col-span-2">
        <label for="title" class="label">Titre</label>
        <input id="title" name="title" type="text" required value="{{ old('title', $book->title ?? '') }}" class="field">
    </div>

    <div>
        <label for="author" class="label">Auteur</label>
        <input id="author" name="author" type="text" required value="{{ old('author', $book->author ?? '') }}" class="field">
    </div>

    <div>
        <label for="isbn" class="label">ISBN ou reference</label>
        <input id="isbn" name="isbn" type="text" value="{{ old('isbn', $book->isbn ?? '') }}" class="field">
    </div>

    <div>
        <label for="category" class="label">Categorie</label>
        <input id="category" name="category" type="text" value="{{ old('category', $book->category ?? '') }}" class="field">
    </div>

    <div>
        <label for="language" class="label">Langue</label>
        <input id="language" name="language" type="text" value="{{ old('language', $book->language ?? '') }}" class="field">
    </div>

    <div>
        <label for="total_copies" class="label">Nombre d exemplaires</label>
        <input id="total_copies" name="total_copies" type="number" min="1" required value="{{ old('total_copies', $book->total_copies ?? 1) }}" class="field">
    </div>

    <div>
        <label for="shelf_location" class="label">Emplacement</label>
        <input id="shelf_location" name="shelf_location" type="text" value="{{ old('shelf_location', $book->shelf_location ?? '') }}" class="field">
    </div>

    <div>
        <label for="loan_duration_days" class="label">Duree d emprunt autorisee</label>
        <input id="loan_duration_days" name="loan_duration_days" type="number" min="1" required value="{{ old('loan_duration_days', $book->loan_duration_days ?? 7) }}" class="field">
    </div>

    <div>
        <label for="late_fee_per_day" class="label">Penalite par jour de retard</label>
        <input id="late_fee_per_day" name="late_fee_per_day" type="number" min="0" step="0.01" required value="{{ old('late_fee_per_day', $book->late_fee_per_day ?? 0) }}" class="field">
    </div>

    <div>
        <label for="acquired_at" class="label">Date d acquisition</label>
        <input id="acquired_at" name="acquired_at" type="date" value="{{ old('acquired_at', isset($book) ? $book->acquired_at?->format('Y-m-d') : '') }}" class="field">
    </div>

    <div class="md:col-span-2">
        <label for="description" class="label">Description</label>
        <textarea id="description" name="description" rows="4" class="field">{{ old('description', $book->description ?? '') }}</textarea>
    </div>
</div>
