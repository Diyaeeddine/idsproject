<?php

namespace App\Http\Controllers;

use App\Models\Demande;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\ChampPersonnalise;

class DemandeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $demands = Demande::with('users')->latest()->get();
        return view('admin.demandes.show-demande', compact('demands'));
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $users = User::all();
        return view('admin.demandes.add-demande', compact('users'));
    }

    /**
     * Store a newly created resource in storage.
     */
public function store(Request $request)
{
    // Valider que le titre est présent
    $request->validate([
        'titre' => 'required|string|max:255',
    ]);

    // Créer la demande avec le titre
    $demande = Demande::create([
        'titre' => $request->input('titre'),
        'user_id' => null,
    ]);

    // Récupérer les champs personnalisés
    $fields = $request->input('fields', []);

    foreach ($fields as $field) {
        ChampPersonnalise::create([
            'key' => $field['key'],
            // 'value' => $field['value'],
            'demande_id' => $demande->id,
        ]);
    }

    return redirect()->back()->with('success', 'Demande créée avec succès');
}

    /**
     * Display the specified resource.
     */
    public function show($id = null)
    {

    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Demande $demande)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Demande $demande)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Demande $demande)
    {
        //
    }

    public function affecterPage($id = null)
    {
        $demandes = Demande::with('champs')->latest()->get(); // liste des formulaires
        $users = User::All();

        $selectedDemande = $id ? Demande::with('champs')->findOrFail($id) : null;

        return view('admin.demandes.affecter-demande', compact('demandes', 'users', 'selectedDemande'));
    }

    public function affecterUsers(Request $request, $id)
    {
        $demande = Demande::findOrFail($id);
        $userIds = json_decode($request->input('user_ids'), true);
        $demande->users()->sync($userIds);
        return redirect()->route('demandes.affecter', $id)->with('success', 'Utilisateurs affectés avec succès à la demande.');

    }
public function affecterChamps(Request $request, $demandeId)
{
    $demande = Demande::findOrFail($demandeId);
    $userId = $request->input('user_id');
    $champsIds = $request->input('champs_ids'); // les champs sélectionnés

    // Vérifie si ces champs ont déjà été affectés
    $alreadyAssignedChamps = $demande->champs()
        ->whereIn('id', $champsIds)
        ->whereNotNull('user_id') // Vérifie si un champ a déjà un utilisateur assigné
        ->exists();

    if ($alreadyAssignedChamps) {
        return back()->with('error', 'Certains champs ont déjà été affectés à un autre accès.');
    }

    // Affecter les champs à l'utilisateur sélectionné
    foreach ($champsIds as $champId) {
        $champ = ChampPersonnalise::findOrFail($champId);
        $champ->update([
            'user_id' => $userId,
            'date_affectation' => now(),
        ]);
    }

    return redirect()->route('demandes.affecter', $demande->id)->with('success', 'Champs affectés avec succès.');
}







public function demandePage($id = null)
{
    // Chargez les demandes avec les utilisateurs associés
    $demandes = Demande::with('users')->latest()->get();

    if ($demandes->isEmpty()) {
        abort(404, 'Aucune demande en base');
    }

    $selectedDemande = $id
        ? Demande::with('users')->find($id) // Charge aussi l'utilisateur pour la demande sélectionnée
        : $demandes->first();

    if (!$selectedDemande) {
        return redirect()->route('demande', $demandes->first()->id);
    }

    return view('admin.demandes.show-demande', [
        'demandes' => $demandes,
        'selectedDemande' => $selectedDemande,
    ]);
}


}
