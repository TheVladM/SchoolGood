<form action="{{ $action }}" method="{{ $method }}" class="space-y-6">
    @csrf
    @if ($method === 'PATCH')
        @method('PATCH')
    @endif

    <!-- Titre -->
    <div class="form-group">
        <label for="title" class="form-label">Titre du devoir *</label>
        <input 
            type="text" 
            id="title" 
            name="title" 
            value="{{ old('title', $homework?->title) }}"
            class="field @error('title') border-red-500 @enderror"
            placeholder="ex: Problèmes de mathématiques"
            required
        >
        @error('title')
            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
        @enderror
    </div>

    <!-- Description -->
    <div class="form-group">
        <label for="description" class="form-label">Description</label>
        <textarea 
            id="description" 
            name="description" 
            rows="4"
            class="field @error('description') border-red-500 @enderror"
            placeholder="Détails du devoir..."
        >{{ old('description', $homework?->description) }}</textarea>
        @error('description')
            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
        @enderror
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <!-- Matière -->
        <div class="form-group">
            <label for="subject" class="form-label">Matière</label>
            <input 
                type="text" 
                id="subject" 
                name="subject" 
                value="{{ old('subject', $homework?->subject) }}"
                class="field @error('subject') border-red-500 @enderror"
                placeholder="ex: Mathématiques"
            >
            @error('subject')
                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Enseignant -->
        <div class="form-group">
            <label for="teacher_id" class="form-label">Enseignant *</label>
            <select 
                id="teacher_id" 
                name="teacher_id"
                class="field @error('teacher_id') border-red-500 @enderror"
                required
            >
                <option value="">-- Sélectionner un enseignant --</option>
                @foreach ($teachers as $teacher)
                    <option 
                        value="{{ $teacher->id }}"
                        @selected(old('teacher_id', $homework?->teacher_id) == $teacher->id)
                    >
                        {{ $teacher->name }}
                    </option>
                @endforeach
            </select>
            @error('teacher_id')
                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <!-- Classe -->
        <div class="form-group">
            <label for="classroom_id" class="form-label">Classe *</label>
            <select 
                id="classroom_id" 
                name="classroom_id"
                class="field @error('classroom_id') border-red-500 @enderror"
                required
            >
                <option value="">-- Sélectionner une classe --</option>
                @foreach ($classrooms as $classroom)
                    <option 
                        value="{{ $classroom->id }}"
                        @selected(old('classroom_id', $homework?->classroom_id) == $classroom->id)
                    >
                        {{ $classroom->name }} ({{ $classroom->section?->value }})
                    </option>
                @endforeach
            </select>
            @error('classroom_id')
                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Date limite -->
        <div class="form-group">
            <label for="due_date" class="form-label">Date limite *</label>
            <input 
                type="datetime-local" 
                id="due_date" 
                name="due_date" 
                value="{{ old('due_date', $homework?->due_date?->format('Y-m-d\TH:i')) }}"
                class="field @error('due_date') border-red-500 @enderror"
                required
            >
            @error('due_date')
                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>
    </div>

    <!-- Actions -->
    <div class="flex gap-3 pt-4 border-t">
        <button type="submit" class="btn-primary">
            {{ $homework?->exists ? 'Mettre à jour' : 'Créer le devoir' }}
        </button>
        <a href="{{ route('homeworks.index') }}" class="btn-secondary">Annuler</a>
    </div>
</form>
