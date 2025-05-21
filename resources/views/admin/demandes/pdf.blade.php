<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8" />
    <title>Demande #{{ $demande->id }}</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 14px;
            color: #333;
            margin: 0 20px;
        }
        h1, h2, h3 {
            color: #2c3e50;
            margin-bottom: 10px;
            margin-top: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            table-layout: fixed;
            word-wrap: break-word;
        }
        th, td {
            border: 1px solid #ccc;
            padding: 8px;
            text-align: left;
            vertical-align: top;
        }
        th {
            background-color: #f0f0f0;
        }
        .section {
            margin-bottom: 25px;
        }
        .qrcode {
            margin-top: 20px;
            text-align: center;
        }
        .small-text {
            font-size: 12px;
            color: #666;
            margin-top: 30px;
            text-align: center;
        }
    </style>
</head>
<body>

<h1>Détails de la demande #{{ $demande->id }}</h1>

<div class="section">
    <h2>Informations générales</h2>
    <table>
        <tbody>
            <tr>
                <th style="width: 25%;">Titre</th>
                <td style="width: 75%;">{{ $demande->titre }}</td>
            </tr>
            <tr>
                <th>Date de création</th>
                <td>{{ $demande->created_at ? $demande->created_at->format('d/m/Y à H:i') : 'Non disponible' }}</td>
            </tr>
        </tbody>
    </table>
</div>

<div class="section">
    <h2>Utilisateurs affectés et durée de complétion</h2>
    <table>
        <thead>
            <tr>
                <th style="width: 30%;">Utilisateur</th>
                <th style="width: 20%;">Date affectation</th>
                <th style="width: 20%;">Date fin tâche</th>
                <th style="width: 30%;">Durée prise</th>
            </tr>
        </thead>
        <tbody>
            @forelse($usersDurations as $ud)
                <tr>
                    <td>{{ $ud['user']->name }} {{ $ud['user']->prenom ?? '' }}</td>
                    <td>{{ \Carbon\Carbon::parse($ud['assigned_at'])->format('d/m/Y H:i') }}</td>
                    <td>{{ \Carbon\Carbon::parse($ud['completed_at'])->format('d/m/Y H:i') }}</td>
                    <td>{{ $ud['duration'] }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" style="text-align:center;">Aucun utilisateur affecté</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="qrcode">
    <h3>Suivez votre demande via ce QR code :</h3>
    <img src="data:image/png;base64,{{ $qrcode }}" alt="QR Code demande" />
</div>

<p class="small-text">
    Ce QR code permet à chaque utilisateur affecté de suivre le temps de complétion de sa partie du formulaire.
</p>

</body>
</html>
