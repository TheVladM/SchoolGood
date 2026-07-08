<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Liste des administrateurs</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; color: #222; margin: 20px; }
        .header { display: flex; align-items: center; justify-content: space-between; margin-bottom: 20px; }
        .header img { max-height: 50px; }
        .header .title { text-align: right; }
        .title h1 { margin: 0; font-size: 18px; }
        .title p { margin: 2px 0 0; font-size: 12px; color: #555; }
        .summary { margin-bottom: 12px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { padding: 8px 10px; border: 1px solid #ddd; text-align: left; }
        th { background: #f4f4f4; }
        tr:nth-child(even) { background: #fafafa; }
        .status-active { color: #127a2c; font-weight: bold; }
        .footer { position: fixed; bottom: 0; left: 0; right: 0; font-size: 10px; text-align: center; color: #555; }
        .footer .signature { float: right; }
        .meta { margin-bottom: 6px; }
    </style>
</head>
<body>
    <div class="header">
        <div>
            <img src="{{ $logo }}" alt="Logo de l'école">
        </div>
        <div class="title">
            <h1>Liste des administrateurs</h1>
            <p>Date d'impression : {{ $date }}</p>
            <p>Total : {{ $total }} administrateur(s)</p>
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th>N°</th>
                <th>Photo</th>
                <th>Nom</th>
                <th>Prénom / Login</th>
                <th>Rôle</th>
                <th>Mobile</th>
                <th>AlanyaID</th>
                <th>Statut</th>
            </tr>
        </thead>
        <tbody>
            @foreach($admins as $index => $admin)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>
                        @if($admin->photoURL)
                            <img src="{{ $admin->photoURL }}" alt="Photo" style="width:40px;height:40px;border-radius:50%;object-fit:cover;">
                        @else
                            <div style="width:40px;height:40px;border-radius:50%;background:#ddd;display:flex;align-items:center;justify-content:center;color:#555;">
                                {{ strtoupper(substr($admin->nom,0,1)) }}
                            </div>
                        @endif
                    </td>
                    <td>{{ $admin->nom }}</td>
                    <td>{{ $admin->username }}</td>
                    <td>{{ $admin->typeAdmin ?? '—' }}</td>
                    <td>{{ $admin->mobile ?? '—' }}</td>
                    <td>{{ $admin->alanyaID ?? '—' }}</td>
                    <td class="status-active">{{ $admin->actif ? 'Actif' : 'Inactif' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        <span>Page {PAGE_NUM} / {PAGE_COUNT}</span>
        <span class="signature">Signature du directeur</span>
    </div>
</body>
</html>
