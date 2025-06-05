<?php

    namespace App\Http\Controllers;
    
    use Illuminate\Http\Request;
    use App\Models\Demandeur;
    use App\Models\Proprietaire;
    use App\Models\Navire;
    use App\Models\Gardien;
    use App\Models\Contrat;
    use Illuminate\Support\Facades\DB;
    use Illuminate\Support\Facades\Validator;
    use Barryvdh\DomPDF\Facade\Pdf;
    
    class ContratController extends Controller
    {
        public function create()
        {
        return view('user.contrats.create');
        }
        public function store(Request $request)
{
    // dd($request->all());

    $validator = Validator::make($request->all(), [
        'type_contrat' => 'required|in:randonnee,accostage',

        'nom_demandeur' => 'required|string|max:255',
        'cin_pass_demandeur' => 'nullable|string|max:50',
        'tel_demandeur' => 'nullable|string|max:20',
        'adresse_demandeur' => 'nullable|string|max:255',
        'email_demandeur' => 'nullable|email|max:255',

        'nom_proprietaire' => 'nullable|string|max:255',
        'tel_proprietaire' => 'nullable|string|max:20',
        'nationalite_proprietaire' => 'nullable|string|max:100',
        'cin_pass_proprietaire' => 'nullable|string|max:50',
        'validite_cin' => 'nullable|date',

        'nom_societe' => 'nullable|string|max:255',
        'ice' => 'nullable|string|max:50',
        'caution_solidaire' => 'nullable|string|max:255',
        'num_police' => 'nullable|string|max:50',
        'com_assurance' => 'nullable|string|max:255',
        'echeance' => 'nullable|string|max:100',

        'nom_navire' => 'required|string|max:255',
        'type_navire' => 'nullable|string|max:100',
        'pavillon' => 'nullable|string|max:100',
        'longueur' => 'nullable|numeric|min:0',
        'largeur' => 'nullable|numeric|min:0',
        'tirant_eau' => 'nullable|numeric|min:0',
        'tirant_air' => 'nullable|numeric|min:0',
        'jauge_brute' => 'nullable|numeric|min:0',
        'annee_construction' => 'nullable|integer|min:1900|max:' . date('Y'),
        'port' => 'nullable|string|max:100',
        'numero_immatriculation' => 'nullable|string|max:100',

        'marque_moteur' => 'nullable|string|max:100',
        'type_moteur' => 'nullable|string|max:100',
        'numero_serie_moteur' => 'nullable|string|max:100',
        'puissance_moteur' => 'nullable|string|max:50',

        'equipage' => 'nullable|integer|min:0',
        'passagers' => 'nullable|integer|min:0',
        'total_personnes' => 'nullable|integer|min:0',

        'Ponton' => 'nullable|string|max:100',
        'num_poste' => 'nullable|string|max:50',

        'date_debut' => 'required|date',
        'date_fin' => 'required|date|after_or_equal:date_debut',

        'nom_gardien' => 'nullable|string|max:255',
        'cin_pass_gardien' => 'nullable|string|max:50',
        'num_tele_gardien' => 'nullable|string|max:20',

        'signe_par' => 'nullable|string|in:demandeur,proprietaire,gardien',
        'date_signature' => 'nullable|date',

        'majoration_stationnement' => 'nullable|in:25,50,100',

        'autres_prestations' => 'nullable|array',
    ]);

    if ($validator->fails()) {
        return redirect()->back()
            ->withErrors($validator)
            ->withInput()
            ->with('error', 'Erreur de validation');
    }

    $contrat = DB::transaction(function () use ($request) {
        $demandeur = Demandeur::create([
            'nom' => $request->nom_demandeur,
            'cin' => $request->cin_pass_demandeur,
            'tel' => $request->tel_demandeur,
            'passeport' => $request->cin_pass_demandeur,
            'adresse' => $request->adresse_demandeur,
            'email' => $request->email_demandeur,
        ]);
    
        $proprietaire = null;
        if ($request->filled('nom_proprietaire') || $request->filled('nom_societe')) {
            $type_proprietaire = $request->filled('nom_societe') ? 'morale' : 'physique';
    
            $proprietaire = Proprietaire::create([
                'type' => $type_proprietaire,
                'nom' => $request->nom_proprietaire,
                'tel' => $request->tel_proprietaire,
                'nom_societe' => $request->nom_societe,
                'ice' => $request->ice,
                'nationalite' => $request->nationalite_proprietaire,
                'cin' => $request->cin_pass_proprietaire,
                'validite_cin' => $request->validite_cin,
                'caution_solidaire' => $request->caution_solidaire,
                'passeport' => $request->cin_pass_proprietaire,
            ]);
        }
    
        $navire = Navire::create([
            'nom' => $request->nom_navire,
            'type' => $request->type_navire,
            'port' => $request->port,
            'numero_immatriculation' => $request->numero_immatriculation,
            'pavillon' => $request->pavillon,
            'longueur' => $request->longueur,
            'largeur' => $request->largeur,
            'tirant_eau' => $request->tirant_eau,
            'tirant_air' => $request->tirant_air,
            'jauge_brute' => $request->jauge_brute,
            'annee_construction' => $request->annee_construction,
            'marque_moteur' => $request->marque_moteur,
            'type_moteur' => $request->type_moteur,
            'numero_serie_moteur' => $request->numero_serie_moteur,
            'puissance_moteur' => $request->puissance_moteur,
        ]);
    
        $gardien = null;
        if ($request->filled('nom_gardien')) {
            $gardien = Gardien::create([
                'nom' => $request->nom_gardien,
                'cin' => $request->cin_pass_gardien,
                'tel' => $request->num_tele_gardien,
                'passeport' => $request->passeport_gardien,
            ]);
        }
    
        $mouvements = [];
    
        if ($request->type_contrat === 'randonnee') {
            $mouvements = [
                'equipage' => $request->equipage,
                'passagers' => $request->passagers,
                'total_personnes' => $request->total_personnes,
                'majoration_stationnement' => $request->majoration_stationnement,
            ];
        }
    
        if ($request->type_contrat === 'accostage') {
            $mouvements = [
                'ponton' => $request->ponton,
                'num_poste' => $request->num_poste,
                'autres_prestations' => $request->autres_prestations ?? [],
                'com_assurance' => $request->com_assurance,
                'num_police' => $request->num_police,
                'echeance' => $request->echeance,
            ];
        }
    
        return Contrat::create([
            'user_id' => auth()->id(),
            'demandeur_id' => $demandeur->id,
            'proprietaire_id' => $proprietaire?->id,
            'navire_id' => $navire->id,
            'gardien_id' => $gardien?->id,
            'type' => $request->type_contrat,
            'mouvements' => json_encode($mouvements),
            'date_debut' => $request->date_debut,
            'date_fin' => $request->date_fin,
            'signe_par' => $request->signe_par,
            'date_signature' => $request->date_signature,
        ]);
    });

    return redirect()->route('contrats.genererPDF', [
        'id' => $contrat->id,
        'type' => $request->type_contrat
    ]);
    
}

public function genererPDF($id, $type)
{
    // dd($id,$type);
    $contrat = Contrat::with(['user', 'proprietaire', 'navire', 'gardien'])->findOrFail($id);

    if ($type === 'accostage') {
        $view = 'user.contrats.accostage';
    } elseif ($type === 'randonnee') {
        $view = 'user.contrats.randonnee';
    }
    

    $pdf = Pdf::loadView($view, ['contrat' => $contrat]);

    return $pdf->download("contrat_{$type}_{$contrat->id}.pdf");
}

    }
