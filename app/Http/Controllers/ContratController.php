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
            'cin_pass' => $request->cin_pass_demandeur,
            'tel' => $request->tel_demandeur,
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
                'cin_pass_phy' => $request->cin_pass_proprietaire_phy,
                'cin_pass_mor' => $request->cin_pass_proprietaire_mor,
                'validite_cin' => $request->validite_cin,
                'caution_solidaire' => $request->caution_solidaire,
                
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
            ]);
        }
    
        $mouvements = [];
    
        if ($request->type_contrat === 'randonnee') {
            $mouvements = [
      
                'num_titre_com' => $request->num_titre_com,
                'equipage' => $request->equipage,
                'passagers' => $request->passagers,
                'total_personnes' => $request->total_personnes,
                'majoration_stationnement' => $request->majoration_stationnement,
            ];
        }
    
        if ($request->type_contrat === 'accostage') {
            $mouvements = [
                'num_abonn'=>$request->num_abonn,
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
            'accepte_le'=>$request->accepte_le,
            'lieu_signature'=>$request->lieu_signature,
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

    $contrat = Contrat::with(['user','demandeur','proprietaire', 'navire', 'gardien'])->findOrFail($id);
    $contrat->mouvements = json_decode($contrat->mouvements, true);


    if ($type === 'accostage') {
        $view = 'user.contrats.accostage';
    } elseif ($type === 'randonnee') {
        $view = 'user.contrats.randonnee';
    }

    $pdf = Pdf::loadView($view, ['contrat' => $contrat]);

    // return $pdf->download("contrat_{$type}_{$contrat->id}.pdf");
    if ($type === 'accostage') {
    return view('user.contrats.accostage',compact('contrat'));

    } elseif ($type === 'randonnee') {
        $view = 'user.contrats.randonnee';
    return view('user.contrats.randonnee',compact('contrat'));

    }
}

    }
