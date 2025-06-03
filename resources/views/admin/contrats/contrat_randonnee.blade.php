<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contrat d'usage de navire - Bouregreg Marina</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            font-size: 10px;
            line-height: 1.2;
            background: white;
            padding: 20px;
        }

        .container {
            max-width: 210mm;
            margin: 0 auto;
            background: white;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
        }

        .logo {
            display: inline-block;
            background: linear-gradient(135deg, #ffcc00, #ff6600, #0066cc);
            color: white;
            padding: 5px 10px;
            border-radius: 15px;
            margin-bottom: 10px;
        }

        .title {
            font-size: 14px;
            font-weight: bold;
            margin-bottom: 5px;
        }

        .subtitle {
            font-size: 12px;
            font-style: italic;
            margin-bottom: 20px;
        }

        .form-section {
            margin-bottom: 15px;
            border: 1px solid #000;
        }

        .section-header {
            background-color: #f0f0f0;
            padding: 3px 5px;
            font-weight: bold;
            font-size: 9px;
            border-bottom: 1px solid #000;
        }

        .form-row {
            display: flex;
            border-bottom: 1px solid #000;
        }

        .form-row:last-child {
            border-bottom: none;
        }

        .form-field {
            padding: 3px 5px;
            border-right: 1px solid #000;
            flex: 1;
            min-height: 20px;
            display: flex;
            align-items: center;
        }

        .form-field:last-child {
            border-right: none;
        }

        .form-field label {
            font-weight: bold;
            margin-right: 5px;
        }

        .form-field input {
            border: none;
            background: transparent;
            flex: 1;
            font-size: 10px;
            padding: 0;
            border-bottom: 1px dotted #666;
            margin-left: 5px;
        }

        .wide-field {
            flex: 2;
        }

        .narrow-field {
            flex: 0.5;
        }

        .checkbox-section {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 5px;
        }

        .checkbox-group {
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .checkbox {
            width: 12px;
            height: 12px;
            border: 1px solid #000;
            display: inline-block;
        }

        .nested-table {
            width: 100%;
            border-collapse: collapse;
        }

        .nested-table td {
            border: 1px solid #000;
            padding: 3px 5px;
            font-size: 9px;
        }

        .center-text {
            text-align: center;
        }

        .small-text {
            font-size: 8px;
        }

        .signature-section {
            margin-top: 20px;
            display: flex;
            justify-content: space-between;
        }

        .signature-box {
            width: 45%;
            text-align: center;
            padding: 10px;
            border: 1px solid #000;
        }

        .footer {
            margin-top: 20px;
            font-size: 8px;
            text-align: center;
            border-top: 1px solid #000;
            padding-top: 10px;
        }

        .footer-line {
            margin-bottom: 5px;
        }
        .marina-logo{
      max-width: 200px;
      max-height: 50px;
    }

        .blue-text {
            color: #0066cc;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <div class="logo"><img src="{{ asset('storage/images/logo-marina.png') }}" class="marina-logo"/>
            </div>
            <div class="title">Contrat d'usage de navire à titre commercial N°................................</div>
            <div class="subtitle">Commercial purpose ship contract</div>
        </div>

        <!-- Demandeur Section -->
        <div class="form-section">
            <div class="section-header">Nom & Prénom du demandeur: ................................................................................................</div>
            <div class="form-row">
                <div class="form-field">
                    <label>N° CIN : (ou / or) :</label>
                    <input type="text">
                </div>
                <div class="form-field">
                    <label>Numéro de téléphone mobile :</label>
                    <input type="text">
                </div>
            </div>
            <div class="form-row">
                <div class="form-field">
                    <label>Passeport:</label>
                    <input type="text">
                </div>
                <div class="form-field">
                    <label>Mobile phone number</label>
                </div>
            </div>
            <div class="form-row">
                <div class="form-field wide-field">
                    <label>Adresse/Address:</label>
                    <input type="text">
                </div>
                <div class="form-field">
                    <label>Email :</label>
                    <input type="text">
                </div>
            </div>
        </div>

        <!-- Propriétaire Section -->
        <div class="form-section">
            <div class="section-header">Informations sur le propriétaire / Ship's owner informations</div>
            <div class="form-row">
                <div class="form-field">
                    <label>Personne physique / Physical person</label>
                </div>
                <div class="form-field">
                    <label>Personne morale/Corporation</label>
                </div>
            </div>
            <div class="form-row">
                <div class="form-field">
                    <label>Nom & Prénom du propriétaire :</label>
                </div>
                <div class="form-field narrow-field">
                    <label>Numéro de tél. :</label>
                </div>
                <div class="form-field">
                    <label>Nom de la société :</label>
                </div>
                <div class="form-field narrow-field">
                    <label>ICE :</label>
                </div>
            </div>
            <div class="form-row">
                <div class="form-field">
                    <label>First & last name of the owner</label>
                </div>
                <div class="form-field narrow-field">
                    <label>Phone number</label>
                </div>
                <div class="form-field">
                    <label>Corporation name</label>
                </div>
                <div class="form-field narrow-field"></div>
            </div>
            <div class="form-row">
                <div class="form-field">
                    <label>Nationalité :</label>
                </div>
                <div class="form-field">
                    <label>N° CIN : (ou / or )</label>
                </div>
                <div class="form-field">
                    <label>Validité jusqu'à:</label>
                </div>
                <div class="form-field">
                    <label>Caution personnelle solidaire</label>
                </div>
                <div class="form-field">
                    <label>N° CIN : (ou / or )</label>
                </div>
            </div>
            <div class="form-row">
                <div class="form-field">
                    <label>Nationality</label>
                </div>
                <div class="form-field">
                    <label>Passeport</label>
                </div>
                <div class="form-field">
                    <label>Valid until</label>
                </div>
                <div class="form-field">
                    <label>Solidarity personal guarantee</label>
                </div>
                <div class="form-field">
                    <label>Passeport</label>
                </div>
            </div>
        </div>

        <!-- Navire Section -->
        <div class="form-section">
            <div class="section-header">Informations sur le navire / Ship's informations</div>
            <div class="form-row">
                <div class="form-field">
                    <label>Nom :</label>
                    <input type="text">
                </div>
                <div class="form-field">
                    <label>Type :</label>
                    <input type="text">
                </div>
                <div class="form-field center-text">
                    <strong>Immatriculation / Registration</strong>
                </div>
                <div class="form-field">
                    <label>Pavillon:</label>
                    <input type="text">
                </div>
            </div>
            <div class="form-row">
                <div class="form-field">
                    <label>Name</label>
                </div>
                <div class="form-field">
                    <label>Type</label>
                </div>
                <div class="form-field">
                    <label>Port / Harbor</label>
                </div>
                <div class="form-field">
                    <label>Numéro / Number</label>
                </div>
                <div class="form-field">
                    <label>Flag</label>
                </div>
            </div>
            <div class="form-row">
                <div class="form-field">
                    <label>Longueur:</label>
                    <input type="text">
                </div>
                <div class="form-field">
                    <label>Largeur :</label>
                    <input type="text">
                </div>
                <div class="form-field">
                    <label>Tirant d'eau :</label>
                    <input type="text">
                </div>
                <div class="form-field">
                    <label>Tirant d'air : Jauge brute:</label>
                    <input type="text">
                </div>
                <div class="form-field">
                    <label>Année de construction:</label>
                    <input type="text">
                </div>
            </div>
            <div class="form-row">
                <div class="form-field">
                    <label>Length</label>
                </div>
                <div class="form-field">
                    <label>Width</label>
                </div>
                <div class="form-field">
                    <label>Draft</label>
                </div>
                <div class="form-field">
                    <label>Air draft Gross tonnage</label>
                </div>
                <div class="form-field">
                    <label>Year of construction</label>
                </div>
            </div>
            <div class="form-row">
                <div class="form-field">
                    <label>Moteur</label>
                </div>
                <div class="form-field">
                    <label>Marque:</label>
                    <input type="text">
                </div>
                <div class="form-field">
                    <label>Type :</label>
                    <input type="text">
                </div>
                <div class="form-field">
                    <label>numéro de série :</label>
                    <input type="text">
                </div>
                <div class="form-field">
                    <label>Puissance :</label>
                    <input type="text">
                </div>
            </div>
            <div class="form-row">
                <div class="form-field">
                    <label>Engin</label>
                </div>
                <div class="form-field">
                    <label>Mark</label>
                </div>
                <div class="form-field">
                    <label>Type</label>
                </div>
                <div class="form-field">
                    <label>Serial number</label>
                </div>
                <div class="form-field">
                    <label>Power</label>
                </div>
            </div>
        </div>

        <!-- Mouvements Section -->
        <div class="form-section">
            <div class="checkbox-section">
                <strong>Mouvements par marées /</strong>
                <div class="checkbox-group">
                    <span class="checkbox"></span>
                    <span>1 seul mouvement</span>
                </div>
                <div class="checkbox-group">
                    <span class="checkbox"></span>
                    <span>2 mouvements</span>
                </div>
                <div class="checkbox-group">
                    <span class="checkbox"></span>
                    <span>Mouvements pleine journée</span>
                </div>
            </div>
            <div class="checkbox-section">
                <strong>Movements per tide</strong>
            </div>
            <div class="checkbox-section">
                <strong>Majoration des frais de stationnement /</strong>
                <div class="checkbox-group">
                    <span class="checkbox"></span>
                    <span>+25%</span>
                </div>
                <div class="checkbox-group">
                    <span class="checkbox"></span>
                    <span>+50%</span>
                </div>
                <div class="checkbox-group">
                    <span class="checkbox"></span>
                    <span>+100%</span>
                </div>
            </div>
            <div class="checkbox-section">
                <strong>Increase of parking fee</strong>
            </div>
        </div>

        <!-- Personnes Section -->
        <div class="form-section">
            <div class="form-row">
                <div class="form-field">
                    <label>Nombre de personnes / Number of persons :</label>
                </div>
                <div class="form-field center-text">
                    <strong>Période facturée / Billing place and period :</strong>
                </div>
            </div>
            <div class="form-row">
                <div class="form-field">
                    <label>Equipage :</label>
                    <input type="text">
                </div>
                <div class="form-field">
                    <label>Passagers :</label>
                    <input type="text">
                </div>
                <div class="form-field">
                    <label>Total:</label>
                    <input type="text">
                </div>
                <div class="form-field">
                    <label>Date Début / Start Date :</label>
                    <input type="text">
                </div>
                <div class="form-field">
                    <label>Date Fin / End Date :</label>
                    <input type="text">
                </div>
            </div>
            <div class="form-row">
                <div class="form-field">
                    <label>Crew</label>
                </div>
                <div class="form-field">
                    <label>Passengers</label>
                </div>
                <div class="form-field"></div>
                <div class="form-field"></div>
                <div class="form-field"></div>
            </div>
        </div>

        <!-- Gardien Section -->
        <div class="form-section">
            <div class="form-row">
                <div class="form-field">
                    <label>Le marin / Gardien :</label>
                    <input type="text">
                </div>
                <div class="form-field wide-field">
                    <label>Nom & Prénom .....................................................................</label>
                </div>
            </div>
            <div class="form-row">
                <div class="form-field">
                    <label>Skipper / Watchman</label>
                </div>
                <div class="form-field">
                    <label>First & last name</label>
                </div>
            </div>
            <div class="form-row">
                <div class="form-field wide-field">
                    <label>N° de téléphone : ................................................</label>
                </div>
                <div class="form-field">
                    <label>CIN ...................................</label>
                </div>
            </div>
            <div class="form-row">
                <div class="form-field">
                    <label>Phone n°</label>
                </div>
                <div class="form-field">
                    <label>Passeport</label>
                </div>
            </div>
        </div>

        <!-- Acceptation Section -->
        <div class="form-section">
            <div class="checkbox-section">
                <span class="checkbox"></span>
                <span><strong>J'accepte de me conformer aux clauses du présent contrat et aux règlements en vigueur relatifs à Bouregreg Marina</strong></span>
            </div>
            <div class="checkbox-section">
                <span><strong>I agree to comply with the terms of this contract and the regulations in force relating to Bouregreg Marina</strong></span>
            </div>
        </div>

        <!-- Signatures -->
        <div class="signature-section">
            <div class="signature-box">
                <strong>Signé par le propriétaire / demandeur:</strong><br>
                <em>Signed by the Owner / person in charge</em><br><br>
                <strong>A Salé le / At Salé The</strong><br>
                Par / By : ................................................
            </div>
            <div class="signature-box">
                <strong>Accepté par Bouregreg Marina :</strong><br>
                <em>Accepted by Bouregreg Marina</em><br><br>
                <strong>Le / The</strong>
            </div>
        </div>

        <!-- Footer Note -->
        <div style="margin-top: 10px; font-size: 8px;">
            <strong>(*)</strong> : Conformément au cahier des tarifs en vigueur / In accordance with the tariff book in force
        </div>

        <!-- Footer -->
        <div class="footer">
            <div class="footer-line">
                <strong>Bouregreg Marina</strong>, siège social : Avenue de Fès Quartier Bnal Bab Lamrissa, Salé
            </div>
            <div class="footer-line">
                Capital social : 4.250.000,00 dh Patente n° : 12059000434 RC N° : 147680 Tel : 05 37 84 90 00 Fax : 05 37 84 90 01
            </div>
            <div class="footer-line">
                IF : N°35580137 - RC N°25785 à Salé, N° de compte : 007 810 0001520000002340 79 Code SWIFT : <span class="blue-text">SGMAMAMC</span>
            </div>
            <div class="footer-line">
                <span class="blue-text">www.bouregregmarina.com</span> Tel : 05 37 84 90 01 Fax : 05 37 78 34 63 10400 Salé
            </div>
        </div>
    </div>
</body>
</html>