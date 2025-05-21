<h1>Demande #{{ $demande->id }} - {{ $demande->titre }}</h1>

<h3>Utilisateurs affectés :</h3>
<ul>
    @foreach ($users as $user)
        <li>{{ $user->name }} ({{ $user->email }})</li>
    @endforeach
</ul>

<h3>QR Code de suivi :</h3>
<img src="data:image/png;base64,{{ base64_encode($qrCode) }}" alt="QR Code" />

<p>Scannez ce QR code pour accéder au formulaire de suivi.</p>
