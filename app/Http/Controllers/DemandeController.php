<?php

namespace App\Http\Controllers;

use App\Models\Demande;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\ChampPersonnalise;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
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
    $demandes = Demande::with('champs')->latest()->get();
    $users = User::all();

    $selectedDemande = $id ? Demande::with('champs')->findOrFail($id) : null;

    $lastUpdatedAt = null;
    if ($selectedDemande) {
        $lastUpdatedAt = $selectedDemande->champs()->whereNotNull('updated_at')
            ->orderBy('updated_at', 'desc')
            ->value('updated_at');
    }

    return view('admin.demandes.affecter-demande', compact('demandes', 'users', 'selectedDemande', 'lastUpdatedAt'));
}



    public function affecterUsers(Request $request, $id)
    {
        $demande = Demande::findOrFail($id);
        $userIds = json_decode($request->input('user_ids'), true);
        $demande->users()->sync($userIds);
        return redirect()->route('demandes.affecter', $id)->with('success', 'Utilisateurs affectés avec succès à la demande.');



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



public function affecterChamps(Request $request, $demandeId)
{
    $userId = $request->input('user_id');

    // Evite les doublons dans demande_user
    DB::table('demande_user')->updateOrInsert(
        ['demande_id' => $demandeId, 'user_id' => $userId],
        ['updated_at' => now(), 'created_at' => now()]
    );

    foreach ($request->input('champs') as $champId => $valeurSoumis) {
        $champ = DB::table('champ_personnalises')
            ->where('id', $champId)
            ->where('demande_id', $demandeId)
            ->first();

        if ($champ && $champ->value !== $valeurSoumis) {
            DB::table('champ_personnalises')
                ->where('id', $champId)
                ->update([
                    'value' => $valeurSoumis,
                    'user_id' => $userId,
                    'updated_at' => now()
                ]);
        }
    }

    $lastUpdatedAt = DB::table('champ_personnalises')
        ->where('demande_id', $demandeId)
        ->whereNotNull('updated_at')
        ->orderByDesc('updated_at')
        ->value('updated_at');

return redirect()
    ->route('demandes.affecter', $demandeId)
    ->with('success', 'Champs affectés avec succès.')
    ->with('lastUpdatedAt', now())   // Stocke maintenant
    ->with('toast_message', true);  // Juste un flag


}









}
