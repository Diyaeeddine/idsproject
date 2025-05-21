<h1>Suivi de la demande #{{ $demande->id }}</h1>
<p><strong>Demandeur :</strong> {{ $user->name }}</p>

<form action="{{ route('suivi.submit', ['demandeId' => $demande->id, 'userId' => $user->id]) }}" method="POST" enctype="multipart/form-data">
    @csrf

    @foreach($demande->champs as $champ)
        <div>
            <label for="champ_{{ $champ->id }}">{{ $champ->key }}</label>
            <input type="text" name="champs[{{ $champ->id }}]" id="champ_{{ $champ->id }}" value="{{ old('champs.' . $champ->id, $champ->value) }}" required>
        </div>
    @endforeach

    <div>
        <label for="fichier">Télécharger un fichier :</label>
        <input type="file" name="fichier" id="fichier">
    </div>

    <button type="submit">Soumettre</button>
</form>
