<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Contrat;
use App\Models\Facture;
use App\Models\FactureItem;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class FactureController extends Controller
{
    /**
     * Display a list of the authenticated user's invoices.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $user = $request->user();
        $factures = $user->factures()->with('contrat.navire')->latest()->get();
        return view('user.factures.index', ['factures' => $factures]);
    }

    /**
     * Show the form for creating a new invoice for a specific contract.
     *
     * @param  \App\Models\Contrat  $contrat
     * @return \Illuminate\View\View
     */
    public function create(Contrat $contrat)
    {
        $contrat->load('demandeur', 'navire');
        $latestInvoice = Facture::whereYear('created_at', date('Y'))->whereMonth('created_at', date('m'))->latest('id')->first();
        $nextInvoiceNumber = $latestInvoice ? (int)substr($latestInvoice->numero_facture, -4) + 1 : 1;
        $invoiceNumber = 'FAC/' . date('Y/m') . '/' . str_pad($nextInvoiceNumber, 4, '0', STR_PAD_LEFT);

        return view('user.factures.create', ['contrat' => $contrat, 'invoiceNumber' => $invoiceNumber]);
    }

    /**
     * Store a newly created invoice and immediately download it as a PDF.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Contrat  $contrat
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function store(Request $request, Contrat $contrat)
    {
        $validated = $request->validate([
            'numero_facture' => 'required|string|unique:factures,numero_facture',
            'date_facture' => 'required|date', 'date_echeance' => 'required|date', 'items' => 'required|array|min:1',
            'items.*.description' => 'required|string|max:255', 'items.*.quantite' => 'required|numeric|min:0.01',
            'items.*.prix_unitaire' => 'required|numeric|min:0', 'total_ht' => 'required|numeric',
            'taxe_regionale' => 'required|numeric', 'total_tva' => 'required|numeric', 'total_ttc' => 'required|numeric',
        ]);

        $newlyCreatedFacture = DB::transaction(function() use ($validated, $contrat) {
            $facture = Facture::create([
                'contrat_id' => $contrat->id, 'numero_facture' => $validated['numero_facture'],
                'date_facture' => $validated['date_facture'], 'date_echeance' => $validated['date_echeance'],
                'total_ht' => $validated['total_ht'], 'taxe_regionale' => $validated['taxe_regionale'],
                'total_tva' => $validated['total_tva'], 'total_ttc' => $validated['total_ttc'],
            ]);
            foreach ($validated['items'] as $item) {
                FactureItem::create([
                    'facture_id' => $facture->id, 'description' => $item['description'], 'quantite' => $item['quantite'],
                    'prix_unitaire' => $item['prix_unitaire'], 'montant_ht' => $item['quantite'] * $item['prix_unitaire'],
                ]);
            }
            return $facture;
        });

        // Re-fetch the model to ensure all relationships are loaded cleanly.
        $facture = Facture::find($newlyCreatedFacture->id);

        // Call the private helper method to generate the PDF object.
        $pdf = $this->generateInvoicePdf($facture);
        
        $filename = 'Facture-' . str_replace('/', '-', $facture->numero_facture) . '.pdf';
        return $pdf->download($filename);
    }

    /**
     * Display the specified invoice for the authenticated user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Facture  $facture
     * @return \Illuminate\View\View
     */
    public function show(Request $request, Facture $facture)
    {
        if ($facture->contrat->user_id !== $request->user()->id) {
            abort(403, 'Unauthorized action.');
        }
        $facture->load('items', 'contrat.navire', 'contrat.demandeur');
        return view('user.factures.facture-detail', ['facture' => $facture]);
    }

    /**
     * Display a public view of a single invoice, scannable from the QR Code.
     *
     * @param  \App\Models\Facture  $facture
     * @return \Illuminate\View\View
     */
    public function showPublic(Facture $facture)
    {
        $facture->load('items', 'contrat.navire', 'contrat.demandeur');
        return view('user.factures.facture-detail', ['facture' => $facture]);
    }

    /**
     * Generate and download a PDF for a specific, existing invoice.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Facture  $facture
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function downloadPDF(Request $request, Facture $facture)
    {
        if ($facture->contrat->user_id !== $request->user()->id) {
            abort(403, 'Unauthorized action.');
        }

        // Call the private helper method to generate the PDF object.
        $pdf = $this->generateInvoicePdf($facture);

        $filename = 'Facture-' . str_replace('/', '-', $facture->numero_facture) . '.pdf';
        return $pdf->download($filename);
    }

    /**
     * NEW: Private helper method to handle PDF generation.
     * This avoids repeating code in store() and downloadPDF().
     *
     * @param  \App\Models\Facture  $facture
     * @return \Barryvdh\DomPDF\PDF
     */
    private function generateInvoicePdf(Facture $facture)
    {
        // Load all necessary data for the PDF template.
        $facture->load('items', 'contrat.navire', 'contrat.demandeur');
        
        // The QR code should always point to the public URL so anyone can scan it.
        $url = route('factures.show', $facture);
        $qrCode = QrCode::size(80)->generate($url);

        // Prepare the data array to pass to the view.
        $data = [
            'facture' => $facture,
            'qrCode' => $qrCode,
        ];

        $pdf = Pdf::loadView('user.factures.pdf_template', $data);
        $pdf->setPaper('a4', 'portrait');
        
        // Return the PDF object to the calling method.
        return $pdf;
    }
}