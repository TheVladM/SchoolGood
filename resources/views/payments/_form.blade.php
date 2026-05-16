<div class="grid gap-6 md:grid-cols-2">
    <div class="md:col-span-2">
        <label for="student_id" class="label">Eleve</label>
        <select id="student_id" name="student_id" required class="field">
            <option value="">Selectionner</option>
            @foreach ($students as $student)
                <option value="{{ $student->id }}" @selected(old('student_id', $payment->student_id ?? '') == $student->id)>
                    {{ $student->full_name }} - {{ $student->classroom?->name }}
                </option>
            @endforeach
        </select>
    </div>

    <div>
        <label for="type" class="label">Type de paiement</label>
        <select id="type" name="type" required class="field">
            <option value="">Selectionner</option>
            @foreach ($types as $value => $label)
                <option value="{{ $value }}" @selected(old('type', $payment->type?->value ?? '') === $value)>
                    {{ $label }}
                </option>
            @endforeach
        </select>
    </div>

    <div>
        <label for="amount" class="label">Montant</label>
        <input id="amount" name="amount" type="number" min="0" step="0.01" required value="{{ old('amount', $payment->amount ?? '') }}" class="field">
    </div>

    <div>
        <label for="reference" class="label">Reference du paiement</label>
        <input id="reference" name="reference" type="text" value="{{ old('reference', $payment->reference ?? '') }}" class="field">
    </div>

    <div>
        <label for="method" class="label">Mode de paiement</label>
        <select id="method" name="method" required class="field">
            <option value="">Selectionner</option>
            @foreach ($methods as $value => $label)
                <option value="{{ $value }}" @selected(old('method', $payment->method?->value ?? '') === $value)>
                    {{ $label }}
                </option>
            @endforeach
        </select>
    </div>

    <div>
        <label for="account_reference" class="label">Numero de compte ou transaction</label>
        <input id="account_reference" name="account_reference" type="text" value="{{ old('account_reference', $payment->account_reference ?? '') }}" class="field">
    </div>

    <div>
        <label for="status" class="label">Statut</label>
        <select id="status" name="status" required class="field">
            <option value="">Selectionner</option>
            @foreach ($statuses as $value => $label)
                <option value="{{ $value }}" @selected(old('status', $payment->status?->value ?? '') === $value)>
                    {{ $label }}
                </option>
            @endforeach
        </select>
    </div>

    <div class="md:col-span-2">
        <label for="notes" class="label">Notes</label>
        <textarea id="notes" name="notes" rows="4" class="field">{{ old('notes', $payment->notes ?? '') }}</textarea>
    </div>
</div>
