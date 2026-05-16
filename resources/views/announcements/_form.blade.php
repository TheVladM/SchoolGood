<div class="grid gap-6 md:grid-cols-2">
    <div class="md:col-span-2">
        <label for="title" class="label">Titre du message</label>
        <input id="title" name="title" type="text" required value="{{ old('title', $announcement->title ?? '') }}" class="field">
    </div>

    <div>
        <label for="audience" class="label">Destinataires</label>
        <select id="audience" name="audience" required class="field">
            <option value="">Selectionner</option>
            @foreach ($audiences as $value => $label)
                <option value="{{ $value }}" @selected(old('audience', $announcement->audience?->value ?? '') === $value)>
                    {{ $label }}
                </option>
            @endforeach
        </select>
    </div>

    <div>
        <label for="classroom_id" class="label">Classe cible</label>
        <select id="classroom_id" name="classroom_id" class="field">
            <option value="">Aucune</option>
            @foreach ($classrooms as $classroom)
                <option value="{{ $classroom->id }}" @selected(old('classroom_id', $announcement->classroom_id ?? '') == $classroom->id)>
                    {{ $classroom->name }} - {{ $classroom->level }}
                </option>
            @endforeach
        </select>
    </div>

    <div class="md:col-span-2">
        <label for="content" class="label">Contenu</label>
        <textarea id="content" name="content" rows="6" required class="field">{{ old('content', $announcement->content ?? '') }}</textarea>
    </div>
</div>
