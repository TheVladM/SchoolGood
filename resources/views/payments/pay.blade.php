@extends('layouts.app')

@section('title', 'Payer en ligne | SchoolGood')
@section('topbar_title', 'Paiement mobile')

@section('content')
    <x-form-shell
        title="Payer avec Orange Money ou MTN MoMo"
        description="Le paiement est confirmé automatiquement par l’opérateur (webhook). Un reçu PDF sera disponible après validation."
        :action="route('payments.mobile.store')"
        :cancel-url="route('payments.declare')"
        submit-label="Lancer le paiement"
        max-width="max-w-2xl"
    >
        <div class="grid gap-6">
            <div>
                <label for="student_id" class="label">Enfant</label>
                <select id="student_id" name="student_id" class="field" required>
                    @foreach ($children as $child)
                        <option value="{{ $child->id }}" @selected(old('student_id') == $child->id)>
                            {{ $child->full_name }} · {{ $child->classroom?->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div>
                <label for="type" class="label">Type</label>
                <select id="type" name="type" class="field" required>
                    @foreach ($types as $value => $label)
                        <option value="{{ $value }}" @selected(old('type') === $value)>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label for="amount" class="label">Montant (FCFA)</label>
                <input id="amount" name="amount" type="number" min="1" step="1" class="field" value="{{ old('amount') }}" required>
            </div>
            <div>
                <label for="method" class="label">Opérateur</label>
                <select id="method" name="method" class="field" required>
                    @foreach ($methods as $value => $label)
                        <option value="{{ $value }}" @selected(old('method') === $value)>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label for="payer_phone" class="label">Numéro mobile du payeur</label>
                <input id="payer_phone" name="payer_phone" type="tel" class="field" placeholder="6XXXXXXXX" value="{{ old('payer_phone', auth()->user()->phone) }}" required>
                <p class="mt-1 text-xs text-slate-500">Numéro Orange ou MTN utilisé pour valider la transaction.</p>
            </div>
        </div>
    </x-form-shell>

    <p class="mt-4 text-center text-sm text-slate-500">
        Vous pouvez aussi <a href="{{ route('payments.declare') }}" class="text-indigo-600 underline">déclarer un paiement manuel</a> avec référence.
    </p>
@endsection
