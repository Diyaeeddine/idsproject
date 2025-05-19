<?php

namespace App\Http\Controllers;

use App\Models\Demande;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\ChampPersonnalise;
use Illuminate\Support\Facades\DB;
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
    $request->validate([
        'titre' => 'required|string|max:255',
    ]);

    $demande = Demande::create([
        'titre' => $request->input('titre'),
        'user_id' => null,
    ]);

    $fields = $request->input('fields', []);

    foreach ($fields as $field) {
        ChampPersonnalise::create([
            'key' => $field['key'],
            'value' => null,
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

    // public function affecterUsers(Request $request, $id)
    // {
    //     $demande = Demande::findOrFail($id);
    //     $userIds = json_decode($request->input('user_ids'), true);
    //     $demande->users()->sync($userIds);
    //     return redirect()->route('demandes.affecter', $id)->with('success', 'Utilisateurs affectés avec succès à la demande.');
        
    // }    
    



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
public function affecterChamps(Request $request, $demandeId)
{

    $userId = $request->input('user_id');

    $demande = Demande::findOrFail($demandeId);

//syncWithoutDetaching() ajoute un enregistrement si inexistant, sans supprimer les autres, et met à jour created_at / updated_at grâce à withTimestamps() dans la relation.
    // Attache sans dupliquer si l'entrée existe déjà
    $demande->users()->syncWithoutDetaching([$userId]);


    foreach ($request->input('champs') as $champId => $valeurSoumis) {
        $champ = ChampPersonnalise::where('id', $champId)
            ->where('demande_id', $demandeId)
            ->first();

        if ($champ && $champ->value !== $valeurSoumis) {
            $champ->update([
                'value' => $valeurSoumis,
                'user_id' => $userId,
                'updated_at' => now(),
            ]);
        }
    }

    return redirect()->back()->with('success', 'Demande et champs mis à jour avec succès.');
}


}
