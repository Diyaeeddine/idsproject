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
    public function userDemandes()
    {
        $user=auth()->user();
        $mesdemandes = $user->demandes()->paginate(10);
        return view('user.demandes', compact('mesdemandes'));
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

    public function show($id = null)
    {

    }

    public function edit(Demande $demande)
    {
        //
    }


    public function update(Request $request, Demande $demande)
    {
        //
    }

    public function destroy(Demande $demande)
    {
        //
    }

    public function affecterPage($id = null)
    {
        $demandes = Demande::with('champs')->latest()->get(); 
        $users = User::where('role','user')->get();

        $selectedDemande = $id ? Demande::with('champs')->findOrFail($id) : null;

        return view('admin.demandes.affecter-demande', compact('demandes', 'users', 'selectedDemande'));
    }


public function demandePage($id = null)
{
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
    // 1. Récupérer les données du formulaire
    $userId = $request->input('user_id');
    $selectedChampIds = $request->input('champs_selected', []);
    
    // 2. Vérifier que les données sont correctes
    $request->validate([
        'user_id' => 'required|exists:users,id',
        'champs_selected' => 'required|array|min:1',
        'champs_selected.*' => 'exists:champ_personnalises,id'
    ]);

    try {
        // 3. Commencer une transaction (pour pouvoir annuler si ça échoue)
        DB::beginTransaction();
        
        // 4. Vérifier que la demande existe bien
        $demande = DB::table('demandes')->find($demandeId);
        if (!$demande) {
            return redirect()->back()->with('error', 'Cette demande n\'existe pas.');
        }
        
        // 5. Vérifier que les champs appartiennent bien à cette demande
        $champsValides = DB::table('champ_personnalises')
            ->where('demande_id', $demandeId)
            ->whereIn('id', $selectedChampIds)
            ->count();
            
        if ($champsValides !== count($selectedChampIds)) {
            return redirect()->back()->with('error', 'Certains champs ne correspondent pas à cette demande.');
        }
        
        // 6. Affecter les champs à l'utilisateur
        DB::table('champ_personnalises')
            ->whereIn('id', $selectedChampIds)
            ->update([
                'user_id' => $userId,
                'updated_at' => now()
            ]);
        
        // 7. Créer le lien entre la demande et l'utilisateur
        DB::table('demande_user')->updateOrInsert(
            [
                'demande_id' => $demandeId, 
                'user_id' => $userId
            ],
            [
                'created_at' => now(), 
                'updated_at' => now()
            ]
        );
        
        // 8. Calculer le nouveau statut de la demande
        $totalChamps = DB::table('champ_personnalises')
            ->where('demande_id', $demandeId)
            ->count();
            
        $champsAffectes = DB::table('champ_personnalises')
            ->where('demande_id', $demandeId)
            ->whereNotNull('user_id')
            ->count();
        
        // Déterminer le statut
        if ($champsAffectes === 0) {
            $nouveauStatut = 'en_attente';
        } elseif ($champsAffectes === $totalChamps) {
            $nouveauStatut = 'affecte';
        } else {
            $nouveauStatut = 'partiellement_affecte';
        }
        
        // 9. Mettre à jour la date de modification de la demande
        DB::table('demandes')
            ->where('id', $demandeId)
            ->update(['updated_at' => now()]);
        
        // 10. Valider toutes les modifications
        DB::commit();
        
        // 11. Préparer le message de succès
        $user = DB::table('users')->find($userId);
        $userName = $user ? $user->name : 'l\'utilisateur';
        $nombreChamps = count($selectedChampIds);
        
        $message = $nombreChamps === 1 
            ? "1 champ a été affecté à {$userName}" 
            : "{$nombreChamps} champs ont été affectés à {$userName}";
        
        return redirect()
            ->route('demandes.affecter', $demandeId)
            ->with('success', $message);
            
    } catch (\Exception $e) {
        // 12. En cas d'erreur, annuler toutes les modifications
        DB::rollback();
        
        // Enregistrer l'erreur dans les logs
        \Log::error('Erreur affectation champs', [
            'demande_id' => $demandeId,
            'user_id' => $userId,
            'erreur' => $e->getMessage()
        ]);
        
        return redirect()
            ->route('demandes.affecter', $demandeId)
            ->with('error', 'Une erreur s\'est produite lors de l\'affectation des champs.')
            ->withInput();
    }
}
public function remplire(){

    return view('');
}
}