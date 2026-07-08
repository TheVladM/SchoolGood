<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Liste des élèves</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 11px; color: #222; margin: 18px; }
        .header { display: flex; align-items: center; justify-content: space-between; margin-bottom: 18px; }
        .header img { max-height: 48px; }
        .header .title { text-align: right; }
        .header .title h1 { margin: 0; font-size: 16px; }
        .header .title p { margin: 2px 0 0; font-size: 11px; color: #555; }
        table { width: 100%; border-collapse: collapse; font-size: 10px; }
        th, td { padding: 8px 8px; border: 1px solid #dcdcdc; vertical-align: middle; }
        th { background: #f0f0f0; }
        tr:nth-child(even) { background: #fafafa; }
        .photo-cell img { width: 30px; height: 30px; border-radius: 50%; object-fit: cover; }
        .photo-placeholder { width: 30px; height: 30px; border-radius: 50%; background: #d5d5d5; display: inline-flex; align-items: center; justify-content: center; color: #666; font-weight: bold; }
        .footer { position: fixed; bottom: 0; left: 0; right: 0; font-size: 10px; text-align: center; color: #555; }
        .signature { float: right; }
    </style>
</head>
<body>
    <div class="header">
        <div>
            <img src="{{ $logo }}" alt="Logo de l'école">
        </div>
        <div class="title">
            <h1>Liste des élèves</h1>
            <p>Date d'impression : {{ $date }}</p>
            <p>Total : {{ $total }} élève(s)</p>
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th>N°</th>
                <th>Photo</th>
                <th>Matricule</th>
                <th>Nom</th>
                <th>Prénom</th>
                <th>Sexe</th>
                <th>Classe</th>
                <th>Année académique</th>
                <th>Statut</th>
            </tr>
        </thead>
        <tbody>
            @foreach($eleves as $index => $eleve)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td class="photo-cell">
                        @if($eleve->photoURL)
                            <img src="{{ $eleve->photoURL }}" alt="Photo élève">
                        @else
                            <span class="photo-placeholder">{{ strtoupper(substr($eleve->nom, 0, 1)) }}</span>
                        @endif
                    </td>
                    <td>{{ $eleve->matricule }}</td>
                    <td>{{ $eleve->nom }}</td>
                    <td>{{ $eleve->prenom }}</td>
                    <td>{{ $eleve->sexe }}</td>
                    <td>{{ optional($eleve->frequentes->first()?->salle?->classe)->libelle ?? '—' }}</td>
                    <td>{{ optional($eleve->frequentes->first())->idAcademi ?? '—' }}</td>
                    <td>{{ $eleve->actif ? 'Actif' : 'Inactif' }}</td>
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
