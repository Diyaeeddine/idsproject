<?php

namespace App\Http\Controllers;

use App\Models\Demande;
use Illuminate\Http\Request;
use PDF;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class PDFController extends Controller
{
    public function generatePDF($id)
    {
        $demande = Demande::with('users')->findOrFail($id);

        $usersDurations = $demande->usersWithDurations();

        $qrcode = base64_encode(QrCode::format('png')->size(150)->generate(route('demande', $id)));

        $data = [
            'demande' => $demande,
            'usersDurations' => $usersDurations,
            'qrcode' => $qrcode,
        ];

        $pdf = PDF::loadView('admin.demandes.pdf', $data);

        return $pdf->download('demande_' . $id . '.pdf');
    }
}
