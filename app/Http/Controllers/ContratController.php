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
    /**
     * Display a list of the authenticated user's contracts.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        // Get the currently logged-in user from the Request object.
        $user = $request->user();

        // Fetch all contracts for this user, including related boat and requester info.
        $contrats = $user->contrats()->with('navire', 'demandeur')->latest()->get();

        // Pass the contracts data to the view.
        return view('user.contrats.contrats', ['contrats' => $contrats]);
    }

    /**
     * Show the form for creating a new contract.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('user.contrats.create');
    }

    /**
     * Store a new contract in the database and redirect to the invoice creation page.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'type_contrat' => 'required|in:randonnee,accostage',
            'nom_demandeur' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $contrat = DB::transaction(function () use ($request) {
            $demandeur = Demandeur::create([
                'nom' => $request->nom_demandeur,
                'cin' => $request->cin_pass_demandeur,
                'tel' => $request->tel_demandeur,
                'adresse' => $request->adresse_demandeur,
                'email' => $request->email_demandeur,
            ]);
            
            $proprietaire = null;
            if ($request->filled('nom_proprietaire') || $request->filled('nom_societe')) {
                $type_proprietaire = $request->filled('nom_societe') ? 'morale' : 'physique';
                $proprietaire = Proprietaire::create([
                    'type' => $type_proprietaire, 'nom' => $request->nom_proprietaire, 'tel' => $request->tel_proprietaire,
                    'nom_societe' => $request->nom_societe, 'ice' => $request->ice, 'nationalite' => $request->nationalite_proprietaire,
                    'cin_pass_phy' => $request->cin_pass_proprietaire_phy, 'cin_pass_mor' => $request->cin_pass_proprietaire_mor,
                    'validite_cin' => $request->validite_cin, 'caution_solidaire' => $request->caution_solidaire,
                ]);
            }
            
            $navire = Navire::create([
                'nom' => $request->nom_navire, 'type' => $request->type_navire, 'port' => $request->port,
                'numero_immatriculation' => $request->numero_immatriculation, 'pavillon' => $request->pavillon,
                'longueur' => $request->longueur, 'largeur' => $request->largeur, 'tirant_eau' => $request->tirant_eau,
                'tirant_air' => $request->tirant_air, 'jauge_brute' => $request->jauge_brute, 'annee_construction' => $request->annee_construction,
                'marque_moteur' => $request->marque_moteur, 'type_moteur' => $request->type_moteur,
                'numero_serie_moteur' => $request->numero_serie_moteur, 'puissance_moteur' => $request->puissance_moteur,
            ]);
            
            $gardien = null;
            if ($request->filled('nom_gardien')) {
                $gardien = Gardien::create(['nom' => $request->nom_gardien, 'cin' => $request->cin_pass_gardien, 'tel' => $request->num_tele_gardien]);
            }
            
            $mouvements = [];
            if ($request->type_contrat === 'randonnee') {
                $mouvements = ['num_titre_com' => $request->num_titre_com, 'equipage' => $request->equipage, 'passagers' => $request->passagers, 'total_personnes' => $request->total_personnes, 'majoration_stationnement' => $request->majoration_stationnement];
            }
            if ($request->type_contrat === 'accostage') {
                $mouvements = ['num_abonn' => $request->num_abonn, 'ponton' => $request->ponton, 'num_poste' => $request->num_poste, 'autres_prestations' => $request->autres_prestations ?? [], 'com_assurance' => $request->com_assurance, 'num_police' => $request->num_police, 'echeance' => $request->echeance];
            }
            
            return Contrat::create([
                'user_id' => $request->user()->id, 'demandeur_id' => $demandeur->id, 'proprietaire_id' => $proprietaire?->id,
                'navire_id' => $navire->id, 'gardien_id' => $gardien?->id, 'type' => $request->type_contrat, 'mouvements' => json_encode($mouvements),
                'date_debut' => $request->date_debut, 'date_fin' => $request->date_fin, 'signe_par' => $request->signe_par,
                'accepte_le' => $request->accepte_le, 'lieu_signature' => $request->lieu_signature, 'date_signature' => $request->date_signature,
            ]);
        });

        return redirect()->route('factures.create', ['contrat' => $contrat->id])
            ->with('download_contract', [
                'id' => $contrat->id,
                'type' => $contrat->type
            ]);
    }

    /**
     * Generate and download a PDF for a specific, existing contract.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Contrat  $contrat
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function downloadPDF(Request $request, Contrat $contrat)
    {
        // Security Check: Ensure the logged-in user owns this contract
        if ($contrat->user_id !== $request->user()->id) {
            abort(403, 'Unauthorized action.');
        }

        $contrat->load(['user', 'demandeur', 'proprietaire', 'navire', 'gardien']);
        $contrat->mouvements = json_decode($contrat->mouvements, true);
        $view = 'user.contrats.' . $contrat->type;

        if (!view()->exists($view)) {
             abort(404, "Le template de contrat pour '{$contrat->type}' n'a pas été trouvé.");
        }

        $pdf = Pdf::loadView($view, ['contrat' => $contrat]);
        $filename = "contrat_{$contrat->type}_{$contrat->id}.pdf";
        
        return $pdf->download($filename);
    }
}