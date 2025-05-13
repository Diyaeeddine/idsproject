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
        $demands = Demande::with('user')->latest()->get();
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
            'user_id' => null, // Par défaut, pas d'utilisateur affecté
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
        // Afficher les détails de la demande
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Demande $demande)
    {
        // Logic for editing a request (if applicable)
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Demande $demande)
    {
        // Logic for updating a request (if applicable)
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Demande $demande)
    {
        // Logic for deleting a request (if applicable)
    }

    /**
     * Afficher la page d'affectation de la demande.
     */
    public function affecterPage($id = null)
    {
        // Charger les demandes avec leurs champs et utilisateurs affectés
        $demandes = Demande::with(['champs', 'users' => function($query) {
            $query->withPivot('date_affectation');
        }])->latest()->get();

        // Charger tous les utilisateurs
        $users = User::all();

        // Charger la demande sélectionnée avec ses relations
        $selectedDemande = $id ? Demande::with(['champs', 'users' => function($query) {
            $query->withPivot('date_affectation');
        }])->findOrFail($id) : null;

        return view('admin.demandes.affecter-demande', compact('demandes', 'users', 'selectedDemande'));
    }

    /**
     * Affecter plusieurs utilisateurs à une demande.
     */
    public function affecterUser(Request $request, $id)
    {
        // Valider que la demande contient au maximum 3 utilisateurs sélectionnés
        $request->validate([
            'user_id' => 'required|array|max:3', // Limiter à 3 utilisateurs
            'user_id.*' => 'exists:users,id', // Vérifier que les utilisateurs existent
        ]);

        // Trouver la demande
        $demande = Demande::findOrFail($id);

        // Attacher les utilisateurs à la demande via la table pivot
        foreach ($request->input('user_id') as $userId) {
            // Vérifier si l'utilisateur est déjà affecté
            if ($demande->users()->where('user_id', $userId)->exists()) {
                return back()->with('error', 'Un ou plusieurs utilisateurs sont déjà affectés à cette demande.');
            }

            // Ajouter l'affectation dans la table pivot
            $demande->users()->attach($userId, [
                'date_affectation' => now()
            ]);
        }

        // Retourner une confirmation
        return redirect()->route('demandes.affecter', $id)
                         ->with('success', 'Demande affectée avec succès.');
    }

    /**
     * Afficher les informations de la demande sélectionnée.
     */
    public function demandePage($id = null)
    {
        // Charger les demandes avec les utilisateurs et les champs personnalisés associés
        $demandes = Demande::with(['user', 'champs'])->latest()->get();

        if ($demandes->isEmpty()) {
            abort(404, 'Aucune demande en base');
        }

        $selectedDemande = $id
            ? Demande::with(['user', 'champs'])->find($id)
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
