<?php

namespace App\Http\Controllers;

use App\Models\Demande;
use Illuminate\Http\Request;
use PDF;
use Illuminate\Support\Str;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class PDFController extends Controller
{
    public function generatePDF($id)
    {
        $demande = Demande::with('users')->findOrFail($id);
        $usersDurations = $demande->usersWithDurations();

        $qrcode = base64_encode(
            QrCode::format('png')->size(150)->generate(route('demande', $id))
        );

        $data = [
            'demande' => $demande,
            'usersDurations' => $usersDurations,
            'qrcode' => $qrcode,
        ];

        // Assuming the Demande has a 'title' or 'objet' or similar
        $title = $demande->title ?? $demande->objet ?? 'demande';
        $safeTitle = Str::slug($title); // makes it URL-safe
        $filename = $safeTitle . '_' . $id . '.pdf';

        $pdf = PDF::loadView('admin.demandes.pdf', $data);

        return $pdf->download($filename);
    }
}
