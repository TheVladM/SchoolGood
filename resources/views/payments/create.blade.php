@extends('layouts.app')

@section('title', __('payments.create_title') . ' | SchoolGood')
@section('topbar_title', __('nav.payments'))

@section('content')
    <x-form-shell
        :title="__('payments.create_title')"
        :description="__('payments.create_desc')"
        :action="route('payments.store')"
        :cancel-url="route('payments.index')"
        submit-label="{{ __('ui.save') }}"
        max-width="max-w-4xl"
    >
        <div id="tuition-summary" class="hidden rounded-xl border border-indigo-200 bg-indigo-50 p-4 text-sm text-indigo-950">
            <p class="font-semibold">{{ __('payments.remaining_label') }} <span data-balance class="font-mono">-</span> FCFA</p>
            <ul class="mt-2 space-y-1 text-indigo-800" data-installments></ul>
        </div>

        @include('payments._form')
    </x-form-shell>

    @push('scripts')
        <script>
            (function () {
                const studentSelect = document.getElementById('student_id');
                const typeSelect    = document.getElementById('type');
                const amountInput   = document.getElementById('amount');
                const summary       = document.getElementById('tuition-summary');
                const balanceEl     = summary?.querySelector('[data-balance]');
                const installmentsEl = summary?.querySelector('[data-installments]');

                async function refreshTuition() {
                    const studentId = studentSelect?.value;
                    const type = typeSelect?.value;
                    if (!studentId) { summary?.classList.add('hidden'); return; }

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
