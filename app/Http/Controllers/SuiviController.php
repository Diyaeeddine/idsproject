<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SuiviController extends Controller
{
    public function showForm($demandeId, $userId)
{
    $demande = Demande::with('champs')->findOrFail($demandeId);
    $user = User::findOrFail($userId);

    // Afficher les données pré-remplies et le formulaire de suivi
    return view('admin.demandes.suivi', compact('demande', 'user'));
}
public function submitForm(Request $request, $demandeId, $userId)
{
    $demande = Demande::findOrFail($demandeId);
    $user = User::findOrFail($userId);

    // Enregistrer les champs personnalisés remplis
    foreach ($request->input('champs') as $champId => $value) {
        $champ = ChampPersonnalise::findOrFail($champId);
        $champ->value = $value;
        $champ->save();
    }

    // Gérer l'upload du fichier
    if ($request->hasFile('fichier')) {
        $file = $request->file('fichier');
        $path = $file->storeAs('uploads', 'fichier_' . time() . '.' . $file->getClientOriginalExtension());
        // Sauvegarder l'upload avec l'association à l'utilisateur et la demande
        Upload::create([
            'user_id' => $user->id,
            'demande_id' => $demande->id,
            'file_path' => $path,
        ]);
    }

    return redirect()->route('suivi.demande', ['demande' => $demandeId, 'user' => $userId])
        ->with('success', 'Formulaire soumis avec succès');
}


}
