<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Facture {{ $facture->numero_facture }}</title>
    <style>
        @page { margin: 25px; }
        body { font-family: 'Helvetica', 'Arial', sans-serif; font-size: 10px; color: #333; }
        .container { width: 100%; margin: 0 auto; }
        table { width: 100%; border-collapse: collapse; }
        .header { margin-bottom: 20px; }
        .header .logo { width: 150px; display: block; }
        .header .client-address { text-align: right; }
        .invoice-title { font-size: 24px; font-weight: bold; color: #005a9e; margin-top: 20px; margin-bottom: 20px; }
        .info-table td { padding-top: 2px; padding-bottom: 2px; vertical-align: top; }
        .items-table { margin-top: 20px; }
        .items-table th { text-align: left; padding: 8px; background-color: #f3f4f6; border-bottom: 1px solid #dee2e6; }
        .items-table td { padding: 8px; border-bottom: 1px solid #dee2e6; vertical-align: top;}
        .items-table .price-col { text-align: right; }
        .items-table .description-col { width: 50%; }
        .totals-section { width: 45%; float: right; margin-top: 15px; }
        .totals-table td { padding: 6px; }
        .totals-table .label { text-align: left; }
        .totals-table .value { text-align: right; }
        .totals-table .total-ttc td { font-weight: bold; font-size: 14px; border-top: 2px solid #333; }
        .payment-info-container { clear: both; margin-top: 20px; }
        .payment-info-section { background-color: #fef9e7; padding: 15px; border: 1px solid #fceec9; border-radius: 5px; }
        .payment-info-section td { padding: 3px 0; vertical-align: top; }
        .footer { position: fixed; bottom: -25px; left: 0px; right: 0px; width: 100%; text-align: center; font-size: 9px; border-top: 1px solid #ccc; padding-top: 5px; }
        .page-number:before { content: "Page " counter(page); }
    </style>
</head>
<body>
    <div class="container">
        
        <table class="header">
            <tr>
                <td><img src="{{ public_path('images/logo_marina.png') }}" alt="Marina Bouregreg" class="logo"></td>
                <td class="client-address"><strong>BREST FRANCE</strong></td>
            </tr>
        </table>

        <div class="invoice-title">Facture {{ $facture->numero_facture }}</div>

        <table class="info-table">
            <tr>
                <td width="33%"><strong>Date de la facture :</strong></td>
                <td width="33%"><strong>Date d'échéance :</strong></td>
                <td width="33%"><strong>Origine :</strong></td>
            </tr>
            <tr>
                <td>{{ \Carbon\Carbon::parse($facture->date_facture)->format('d/m/Y') }}</td>
                <td>{{ \Carbon\Carbon::parse($facture->date_echeance)->format('d/m/Y') }}</td>
                <td>{{ $facture->contrat?->id ?? 'N/A' }}</td>
            </tr>
        </table>
        
        <table class="items-table">
            <thead>
                <tr>
                    <th class="description-col">Description</th>
                    <th class="price-col">Quantité</th>
                    <th class="price-col">Prix Unitaire</th>
                    <th class="price-col">Montant</th>
                </tr>
            </thead>
            <tbody>
                @foreach($facture->items as $item)
                <tr>
                    <td>{{ $item->description }}</td>
                    <td class="price-col">{{ number_format($item->quantite, 2, ',', ' ') }}</td>
                    <td class="price-col">{{ number_format($item->prix_unitaire, 2, ',', ' ') }} DH</td>
                    <td class="price-col">{{ number_format($item->montant_ht, 2, ',', ' ') }} DH</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <div class="totals-section">
            <table class="totals-table">
                <tr><td class="label">Total HT</td><td class="value">{{ number_format($facture->total_ht, 2, ',', ' ') }} DH</td></tr>
                <tr><td class="label">Taxe régionale 5%</td><td class="value">{{ number_format($facture->taxe_regionale, 2, ',', ' ') }} DH</td></tr>
                <tr><td class="label">TVA 20%</td><td class="value">{{ number_format($facture->total_tva, 2, ',', ' ') }} DH</td></tr>
                <tr class="total-ttc"><td class="label">Total TTC</td><td class="value">{{ number_format($facture->total_ttc, 2, ',', ' ') }} DH</td></tr>
                <tr><td class="label" style="padding-top: 10px;">Payé le {{-- Date Here --}}</td><td class="value" style="padding-top: 10px;">{{-- Amount Here --}}</td></tr>
                <tr><td class="label">Montant dû</td><td class="value">0,00 DH</td></tr>
            </table>
        </div>

        <div class="payment-info-container">
            <div class="payment-info-section">
                <p>Arrêté la présente facture en toutes taxes comprises à la somme de: <strong>{{-- Placeholder for number to words --}}</strong></p>
                <br>
                <p>Les éléments de référence du paiement de votre facture : {{ $facture->numero_facture }}</p>
                <table>
                    <tr>
                        <td style="width: 70%; vertical-align: top;">
                            <p>&bull; <strong>Nº réservation :</strong> {{-- Placeholder --}}</p>
                            <p>&bull; <strong>Nom du Bateau :</strong> {{ $facture->contrat?->navire?->nom ?? 'N/A' }}</p>
                            <p>&bull; <strong>Immatriculation du Bateau :</strong> {{ $facture->contrat?->navire?->numero_immatriculation ?? 'N/A' }}</p>
                            <p>&bull; <strong>Dimensions :</strong> {{ optional($facture->contrat?->navire)->longueur ?? '0' }} m * {{ optional($facture->contrat?->navire)->largeur ?? '0' }} m</p>
                            <p>&bull; <strong>Type Passage :</strong> {{-- Placeholder --}}</p>
                            <p>&bull; <strong>Période Du</strong> {{ optional($facture->contrat)->date_debut ? \Carbon\Carbon::parse($facture->contrat->date_debut)->format('d/m/Y') : 'N/A' }} <strong>Au</strong> {{ optional($facture->contrat)->date_fin ? \Carbon\Carbon::parse($facture->contrat->date_fin)->format('d/m/Y') : 'N/A' }}</p>
                        </td>
                        <td style="width: 30%; text-align: right; vertical-align: middle;">
                            {!! $qrCode !!}
                        </td>
                    </tr>
                </table>
            </div>
        </div>

        <div class="footer">
            Siège Social : La Marina de Bouregreg Avenue de Fès Quartier Rmel Bab Lamrissa, Salé
            <br>
            Société Anonyme au Capital de 20 000 000,00 dhs I Patente n°25198370 I IF N°03380237 I RC N°25785 I ICE 000017097000004 Tel: 05 37 84 99 00 Fax: 05 37 78 58 58
            <br>
            <span class="page-number"></span> / 1
        </div>
    </div>
</body>
</html>