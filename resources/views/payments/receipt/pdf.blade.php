<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <title>Reçu {{ $payment->receipt_number }}</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; color: #1e293b; }
        h1 { font-size: 20px; margin: 0 0 8px; }
        .meta { margin-bottom: 24px; }
        table { width: 100%; border-collapse: collapse; margin-top: 16px; }
        th, td { border: 1px solid #cbd5e1; padding: 8px; text-align: left; }
        th { background: #f1f5f9; }
        .footer { margin-top: 32px; font-size: 10px; color: #64748b; }
    </style>
</head>
<body>
    <h1>{{ $schoolName }}</h1>
    <p class="meta">Reçu de paiement <strong>{{ $payment->receipt_number }}</strong><br>
        Émis le {{ ($payment->paid_at ?? $payment->validated_at)?->format('d/m/Y H:i') }}</p>

    <table>
        <tr><th>Élève</th><td>{{ $payment->student?->full_name }}</td></tr>
        <tr><th>Classe</th><td>{{ $payment->student?->classroom?->name }}</td></tr>
        <tr><th>Parent</th><td>{{ $payment->student?->parent?->name }}</td></tr>
        <tr><th>Type</th><td>{{ $payment->type?->label() }}</td></tr>
        <tr><th>Montant</th><td>{{ number_format((float) $payment->amount, 0, ',', ' ') }} FCFA</td></tr>
        <tr><th>Mode</th><td>{{ $payment->method?->label() }} ({{ $payment->channel?->label() }})</td></tr>
        <tr><th>Référence</th><td>{{ $payment->intent_reference ?? $payment->reference ?? '—' }}</td></tr>
        <tr><th>Transaction opérateur</th><td>{{ $payment->operator_transaction_id ?? '—' }}</td></tr>
        <tr><th>Validé par</th><td>{{ $payment->validatedBy?->name ?? 'Automatique (webhook)' }}</td></tr>
    </table>

    <p class="footer">Document généré par SchoolGood — {{ now()->format('d/m/Y H:i') }}</p>
</body>
</html>
