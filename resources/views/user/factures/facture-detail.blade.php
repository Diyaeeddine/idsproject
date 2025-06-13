<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    {{-- Title now uses the dynamic invoice number --}}
    <title>Facture {{ $facture->numero_facture }}</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Poppins', sans-serif; background-color: #f5f5f5; padding: 20px; line-height: 1.4; }
        .invoice-container { max-width: 800px; margin: 0 auto; background-color: white; padding: 40px; box-shadow: 0 4px 20px rgba(0,0,0,0.1); position: relative; overflow: hidden; }
        .invoice-container::before {
            content: ''; position: absolute; top: 0; left: 0; right: 0; bottom: 0;
            background-image: url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 200 200"><text x="50%" y="50%" font-family="Arial" font-size="24" fill="rgba(200,200,200,0.1)" text-anchor="middle" transform="rotate(-45 100 100)">MARINA BOUREGREG</text></svg>');
            background-repeat: repeat; background-size: 300px 300px; pointer-events: none; z-index: 0;
        }
        .content { position: relative; z-index: 1; }
        .header { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 40px; }
        .logo-section { display: flex; align-items: center; }
        .logo { height: 50px; margin-right: 20px; }
        .customer-info { background-color: #000; color: white; padding: 8px 16px; font-weight: 600; font-size: 14px; }
        .invoice-title { color: #2563eb; font-size: 28px; font-weight: 500; margin-bottom: 30px; }
        .date-section { display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 40px; margin-bottom: 30px; font-size: 14px; }
        .date-item .label { color: #df9842; font-weight: 600; margin-bottom: 5px; }
        .date-item .value { color: #333; font-weight: 400; }
        .invoice-table { width: 100%; border-collapse: collapse; margin-bottom: 30px; font-size: 14px; }
        .invoice-table th { background-color: #e5e7eb; padding: 12px 8px; text-align: left; font-weight: 600; border: 1px solid #d1d5db; }
        .invoice-table th.text-center { text-align: center; }
        .invoice-table th.text-right { text-align: right; }
        .invoice-table td { padding: 15px 8px; border: 1px solid #d1d5db; background-color: white; }
        .invoice-table td.text-center { text-align: center; }
        .invoice-table td.text-right { text-align: right; }
        .totals-section { display: flex; justify-content: flex-end; margin-bottom: 30px; }
        .totals-table { width: 350px; font-size: 14px; }
        .totals-row { display: flex; justify-content: space-between; padding: 8px 0; }
        .totals-row.border-top { border-top: 1px solid #d1d5db; }
        .totals-row.bold { font-weight: 600; }
        .payment-details { font-size: 12px; color: #666; }
        .summary-text { font-size: 14px; margin-bottom: 25px; }
        .summary-text .orange-amount { color: #df9842; font-weight: 600; }
        .details-section { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 40px; }
        .reservation-details { font-size: 14px; }
        .reservation-details .detail-item { margin-bottom: 6px; }
        .reservation-details .label { color: #df9842; font-weight: 600; }
        .qr-code { width: 80px; height: 80px; } /* Removed background, as SVG will be injected */
        .footer { border-top: 2px solid #666; padding-top: 20px; text-align: center; font-size: 12px; color: #666; line-height: 1.6; }
        .footer-line { margin-bottom: 8px; }
        .footer .bold { font-weight: 600; }
    </style>
</head>
<body>
    <div class="invoice-container">
        <div class="content">
            <div class="header">
                <div class="logo-section">
                    <img src="https://i.ibb.co/PvbWFDn3/marina-logo-black.png" alt="Marina Bouregreg Logo" class="logo">
                </div>
                <div class="customer-info">
                    {{-- Data from backend: Using optional() and ?? to prevent errors if data is missing --}}
                    {{ optional($facture->contrat?->demandeur)->nom ?? 'Client non spécifié' }}
                </div>
            </div>

            <h1 class="invoice-title">Facture {{ $facture->numero_facture }}</h1>

            <div class="date-section">
                <div class="date-item">
                    <div class="label">Date de la facture :</div>
                    <div class="value">{{ \Carbon\Carbon::parse($facture->date_facture)->format('d/m/Y') }}</div>
                </div>
                <div class="date-item">
                    <div class="label">Date d'échéance :</div>
                    <div class="value">{{ \Carbon\Carbon::parse($facture->date_echeance)->format('d/m/Y') }}</div>
                </div>
                <div class="date-item">
                    <div class="label">Origine :</div>
                    <div class="value">{{ optional($facture->contrat)->id ?? 'N/A' }}</div>
                </div>
            </div>
            <hr>
            <table class="invoice-table">
                <thead>
                    <tr>
                        <th style="background: none; border: none;">Description</th>
                        <th style="background: none; border: none;" class="text-center">Quantité</th>
                        <th style="background: none; border: none;" class="text-center">Prix Unitaire</th>
                        <th style="background: none; border: none;" class="text-center">Taxes</th>
                        <th style="background: none; border: none;" class="text-right">Montant</th>
                    </tr>
                </thead>
                <tbody>
                    {{-- Loop through invoice items from the backend --}}
                    @foreach($facture->items as $item)
                        <tr>
                            <td style="border: none; background: #fff8f5; border-radius:5px 0 0 5px;">{{ $item->description }}</td>
                            <td style="border: none; background: #fff8f5;" class="text-center">{{ number_format($item->quantite, 2, ',', ' ') }}</td>
                            <td style="border: none; background: #fff8f5;" class="text-center">{{ number_format($item->prix_unitaire, 2, ',', ' ') }} DH</td>
                            <td style="border: none; background: #fff8f5;" class="text-center">Taxe régionale 5%, TVA 20%</td>
                            <td style="border: none; background: #fff8f5; border-radius:0 5px 5px 0;" class="text-right">{{ number_format($item->montant_ht, 2, ',', ' ') }} DH</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <div class="totals-section">
                <div class="totals-table">
                    <div class="totals-row bold">
                        <span>Total HT</span>
                        <span>{{ number_format($facture->total_ht, 2, ',', ' ') }} DH</span>
                    </div>
                    <div class="totals-row border-top">
                        <span>Taxe régionale 5%</span>
                        <span>{{ number_format($facture->taxe_regionale, 2, ',', ' ') }} DH</span>
                    </div>
                    <div class="totals-row border-top">
                        <span>TVA 20%</span>
                        <span>{{ number_format($facture->total_tva, 2, ',', ' ') }} DH</span>
                    </div>
                    <div class="totals-row border-top bold">
                        <span>Total TTC</span>
                        <span>{{ number_format($facture->total_ttc, 2, ',', ' ') }} DH</span>
                    </div>
                    {{-- You would need logic for payments to make these dynamic --}}
                    <div class="totals-row">
                        <div>
                            <div>Payé le ...</div>
                            <div class="payment-details">...</div>
                        </div>
                        <span>...</span>
                    </div>
                    <div class="totals-row border-top bold">
                        <span>Montant dû</span>
                        <span>...</span>
                    </div>
                </div>
            </div>

            <div class="summary-text">
                {{-- NOTE: Converting numbers to words requires a dedicated library. This is a placeholder. --}}
                <p><strong>Arrêté la présente facture en toutes taxes comprises à la somme de :</strong> <span class="orange-amount">(montant en lettres)</span></p>
                <p style="margin-top: 15px;"><strong>Les éléments de référence du paiement de votre facture :</strong> {{ $facture->numero_facture }}</p>
            </div>

            <div class="details-section">
                <div class="reservation-details">
                    {{-- NOTE: These are placeholders for data not in your current database. --}}
                    <div class="detail-item"><span class="label">N° réservation :</span> N/A</div>
                    <div class="detail-item"><span class="label">Nom du Bateau :</span> {{ optional($facture->contrat?->navire)->nom ?? 'N/A' }}</div>
                    <div class="detail-item"><span class="label">Immatriculation du Bateau :</span> {{ optional($facture->contrat?->navire)->numero_immatriculation ?? 'N/A' }}</div>
                    <div class="detail-item"><span class="label">Dimensions :</span> {{ optional($facture->contrat?->navire)->longueur ?? '0' }} m × {{ optional($facture->contrat?->navire)->largeur ?? '0' }} m</div>
                    <div class="detail-item"><span class="label">Type Passage :</span> N/A</div>
                    <div class="detail-item"><span class="label">Période :</span> Du {{ optional($facture->contrat)->date_debut ? \Carbon\Carbon::parse($facture->contrat->date_debut)->format('d/m/Y') : 'N/A' }} <span style="color: #df9842; font-weight: bold;">Au</span> {{ optional($facture->contrat)->date_fin ? \Carbon\Carbon::parse($facture->contrat->date_fin)->format('d/m/Y') : 'N/A' }}</div>
                </div>
                <div class="qr-code">
                    {{-- Displaying the QR Code SVG passed from the controller --}}
                    @isset($qrCode)
                        {!! $qrCode !!}
                    @endisset
                </div>
            </div>

            <div class="footer">
                <div class="footer-line bold">Siège Social : La Marina de Bouregreg Avenue de Fès Quartier Rmel Bab Lamrissa, Salé</div>
                <div class="footer-line">Société Anonyme au Capital de 20 000 000,00 dhs | Patente n°25198370 | IF N°03380237 | RC N°25785 | ICE 000017097000004 Tel : 05 37 84 99 00 Fax : 05 37 78 58 58</div>
                <div class="footer-line bold">Page: 1 sur 1</div>
            </div>
        </div>
    </div>
</body>
</html>