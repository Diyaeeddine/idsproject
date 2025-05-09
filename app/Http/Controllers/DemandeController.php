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

    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $users = User::all();
        return view('admin.demandes.add-demande', compact('users'));
    }

    public function adminIndex()
    {
        $demands = Demande::all();
        return view('admin.demandes.show-demande', compact('demands'));
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
        'user_id' => auth()->id(), // Si vous avez besoin de l'ID de l'utilisateur actuel
    ]);

    // Récupérer les champs personnalisés
    $fields = $request->input('fields', []);

    foreach ($fields as $field) {
        ChampPersonnalise::create([
            'key' => $field['key'],
            'value' => $field['value'],
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
        $demandes = Demande::with('champs')->get(); // liste des formulaires
        $users = User::all();
        $selectedDemande = $id ? Demande::with('champs')->findOrFail($id) : null;

        return view('admin.demandes.affecter-demande', compact('demandes', 'users', 'selectedDemande'));
    }

    public function affecterUser(Request $request, $id)
    {
        $demande = Demande::findOrFail($id);
        $demande->user_id = $request->input('user_id');
        $demande->save();

        return redirect()->route('demandes.affecter', $id)->with('success', 'Formulaire affecté avec succès.');
    }



public function demandePage($id = null)
{
    // Chargez les demandes avec les utilisateurs associés
    $demandes = Demande::with('user')->get();

    if ($demandes->isEmpty()) {
        abort(404, 'Aucune demande en base');
    }

    $selectedDemande = $id
        ? Demande::with('user')->find($id) // Charge aussi l'utilisateur pour la demande sélectionnée
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
