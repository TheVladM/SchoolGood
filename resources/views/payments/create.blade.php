@extends('layouts.app')

@section('title', 'Nouveau paiement | SchoolGood')
@section('topbar_title', 'Nouveau paiement')

@section('content')
    @include('partials.page-header', [
        'title' => 'Enregistrer un paiement',
        'description' => 'Le montant suggéré provient des tarifs de scolarité (niveau et section).',
    ])

    <section class="surface-card mt-6 mx-auto max-w-4xl p-5 lg:p-6" data-reveal>
        <div id="tuition-summary" class="mb-6 hidden rounded-lg border border-indigo-200 bg-indigo-50 p-4 text-sm text-indigo-950">
            <p><strong>Reste à payer (année) :</strong> <span data-balance>-</span> FCFA</p>
            <ul class="mt-2 space-y-1" data-installments></ul>
        </div>

        <form method="POST" action="{{ route('payments.store') }}" class="space-y-6" id="payment-form">
            @csrf
            @include('payments._form')

            <div class="flex gap-3">
                <button type="submit" class="btn-primary">Enregistrer</button>
                <a href="{{ route('payments.index') }}" class="btn-secondary">Annuler</a>
            </div>
        </form>
    </section>

    @push('scripts')
        <script>
            (function () {
                const studentSelect = document.getElementById('student_id');
                const typeSelect = document.getElementById('type');
                const amountInput = document.getElementById('amount');
                const summary = document.getElementById('tuition-summary');
                const balanceEl = summary?.querySelector('[data-balance]');
                const installmentsEl = summary?.querySelector('[data-installments]');

                async function refreshTuition() {
                    const studentId = studentSelect?.value;
                    const type = typeSelect?.value;
                    if (!studentId) {
                        summary?.classList.add('hidden');
                        return;
                    }

                    const url = `{{ url('/paiements/eleves') }}/${studentId}/tarifs` + (type ? `?type=${encodeURIComponent(type)}` : '');
                    const response = await fetch(url, { headers: { 'Accept': 'application/json' } });
                    if (!response.ok) return;

                    const data = await response.json();
                    summary?.classList.remove('hidden');
                    if (balanceEl) balanceEl.textContent = new Intl.NumberFormat('fr-FR').format(data.balance_due ?? 0);

                    if (installmentsEl && Array.isArray(data.installments)) {
                        installmentsEl.innerHTML = data.installments.map(row =>
                            `<li>${row.label} : dû ${row.due} F · payé ${row.paid} F · reste ${row.remaining} F</li>`
                        ).join('');
                    }

                    if (data.expected_amount != null && amountInput && (!amountInput.value || amountInput.dataset.auto === '1')) {
                        amountInput.value = data.expected_amount;
                        amountInput.dataset.auto = '1';
                    }
                }

                studentSelect?.addEventListener('change', refreshTuition);
                typeSelect?.addEventListener('change', refreshTuition);
                amountInput?.addEventListener('input', () => { amountInput.dataset.auto = '0'; });
            })();
        </script>
    @endpush
@endsection
