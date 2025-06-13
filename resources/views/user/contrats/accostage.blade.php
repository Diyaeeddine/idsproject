<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Contrat d'usage de navire</title>
  <style>
    @font-face {
  font-family: 'Nunito';
  src: url('/fonts/Nunito-SemiBold.ttf') format('truetype');
  font-weight: 600;
  font-style: normal;
}
    *{
      font-family: 'Nunito', sans-serif !important;

    }

    body {
      font-family: 'Nunito', sans-serif !important;

      font-weight: 600;
      margin: 0px 40px 40px 40px;
    }

     p {
      text-align: left;
      margin-left: 50px;

    }
    #sous-titre{
      margin-left: 55px;
      padding: 0px;

    }
    .logo {
      text-align: center;
      margin-bottom: 10px;
    }
    .logo img {
        width: 340px;
      height:70px;
    }
    table {
      width: 100%;
      border-collapse: collapse;
      margin-bottom: 15px;
    }
    #eng{
        font-weight:bold !important;
    }
    td, th {
      border: 1px solid #000;
      padding: 1px 0px 1px 5px;
      vertical-align: top;
      font-size: 10px;
    }
    th{
      background: #eee;

    }
    .section-title {
      background: #eee;
      font-weight: bold;
      text-align: left;
    }
    .small {
      font-size: 11px;
      font-style: italic;
    }
    .checkbox {
      display: inline-block;
      width: 12px;
      height: 12px;
      border: 1px solid #000;
      margin-right: 5px;
    }
    .footer {
      font-size: 11px;
      text-align: center;
      margin-top: 30px;
    }
    /* th{
        text-align: start;
    } */
table{
    margin: 0;
}
.no-border-table {
  border-collapse: collapse;
  width: 100%;
}

.no-border-tr {}

.no-border-td {
  border: none;
  padding: 8px;
}

  </style>
</head>
<body>

<div class="logo">
  <img src="{{ asset('storage/images/logo-colorer.png') }}" alt="Marina Bouregreg">
</div>

<p>Contrat d'usage de navire à titre commercial N° {{$contrat->mouvements['num_abonn']}}
    <br><span id="sous-titre">Commercial purposes ship contract</span>
</p>

<table>
  <tr><td colspan="4" style="padding:1px 0px 1px 5px;">Nom & Prénom du demandeur: {{ $contrat->demandeur->nom }}
    <br><span id="eng">First & last name of the person in charge</span></td></tr>
  <tr>
    <td colspan="2" style="padding:1px 0px 1px 5px;">N° CIN : (ou / or) : {{ $contrat->demandeur->cin }}
        <br>
       <b>Passeport</b>
    </td>
    <td colspan="2" style="padding:1px 0px 1px 5px;">Numéro de téléphone mobile : {{ $contrat->demandeur->tel }}
        <br> <em>Mobile phone number</em></td>
  </tr>
  <tr>
    <td colspan="2" style="padding:1px 0px 1px 5px;">Adresse / Address : {{ $contrat->demandeur->adresse }}</td>
    <td style="padding:1px 0px 1px 5px;">Email : {{ $contrat->demandeur->email }}</td>
  </tr>
</table>

<table>
  <tr><th colspan="6" class="section-title" style="padding:1px 0px 1px 5px;">Informations sur le propriétaire / <em>Ship’s owner informations</em></th></tr>
  <tr>
    <th colspan="3">Personne physique / <em>Physical person</em></th>
    <th colspan="3">Personne morale / <em>Corporation</em></th>
  </tr>
  <tr>
    <th colspan="2">Nom & Prénom du propriétaire :
        <br>First & last name of the owner
    </th>
    <th>Numéro de tel : <br>Phone number</th>
    <th>Nom de la société : <br>Corporation name</th>
    <th colspan="2">ICE : </th>
  </tr>
  <tr>
    <td colspan="2">{{ $contrat->proprietaire->nom }}</td>
    <td>{{ $contrat->proprietaire->tel }}</td>
    <td>{{ $contrat->proprietaire->nom_societe }}</td>
    <td>{{ $contrat->proprietaire->ice }}</td>
  </tr>

  <tr>
    <th>Nationalité : <br>Nationality</th>
    <th>N° CIN : (ou / or) <br> Passeport</th>
    <th>Validité jusqu’à : <br>Valid until</th>
    <th>Caution personnelle solidaire : <br>Solidarity personal guarantee</th>
    <th colspan="2">N° CIN : (ou / or) <br> Passeport</th>
  </tr>
  <tr>
    <td>{{ $contrat->proprietaire->nationalite }}</td>
    <td>{{ $contrat->proprietaire->cin_pass_phy }}</td>
    <td>{{ $contrat->proprietaire->validite_cin }}</td>
    <td>{{ $contrat->proprietaire->caution_solidaire }}</td>
    <td>{{ $contrat->proprietaire->cin_pass_mor }}</td>
  </tr>
  <tr>
    <th colspan="3">Compagnie d'Assurance / Insurance Company :</th>
    <th>N° de Police / Policy N° :</th>
    <th>Échéance / Deadline :</th>
  </tr>
  <tr>
    <td colspan="3">{{$contrat->mouvements['com_assurance']}}</td>
    <td>{{$contrat->mouvements['num_police']}}</td>
    <td>{{$contrat->mouvements['echeance']}}</td>
  </tr>
</table>

<table style="border-collapse: collapse; width: 100%; text-align: center;">
    <!-- Titre -->
    <tr>
      <th colspan="7" style="border: 1px solid #000; font-weight: bold; text-align: left; padding:1px 0px 1px 5px;">
        Informations sur le navire / <em>Ship's informations</em>
      </th>
    </tr>
  
    <tr>
      <th rowspan="2">
        <strong>Nom :</strong><br><em>Name</em>
      </th>
      <th rowspan="2">
        <strong>Type :</strong><br><em>Type</em>
      </th>
      <th colspan="2">
        <strong>Immatriculation / <em>Registration</em></strong>
      </th>
      <th colspan="3" rowspan="2" >
        <strong>Pavillon :</strong><br><em>Flag</em>
      </th>
    </tr>

    <tr>
      <td>
        Port / <em>Harbor</em>
      </td>
      <td>
        Numéro / <em>Number</em>
      </td>
    </tr>
    <tr>
        
            <td>{{ $contrat->navire->nom }}</td>
            <td>{{ $contrat->navire->type }}</td>
            <td>{{ $contrat->navire->port }}</td>
            <td >{{ $contrat->navire->numero_immatriculation }}</td>
            <td colspan="3">{{ $contrat->navire->pavillon }}</td>
    </tr>
  

    <tr>
      <th >Longueur :<br><em>Length</em></th>
      <th >Largeur :<br><em>Width</em></th>
      <th >Tirant d’eau :<br><em>Draft</em></th>
      <th >Tirant d’air :<br><em>Air draft</em></th>
      <th >Jauge brute :<br><em>Gross tonnage</em></th>
      <th colspan="2">Année de construction :<br><em>Year of construction</em></th>
    </tr>
    <tr>
      <td >{{ $contrat->navire->longueur }}</td>
      <td >{{ $contrat->navire->largeur }}</td>
      <td >{{ $contrat->navire->tirant_eau }}</td>
      <td >{{ $contrat->navire->tirant_air }}</td>
      <td >{{ $contrat->navire->jauge_brute }}</td>
      <td colspan="2">{{ $contrat->navire->annee_construction }}</td>

    </tr>
  
    <!-- Moteur -->
    <tr>
      <th rowspan="2">Moteur<br><em>Engine</em></th>
      <th>Marque :<br><em>Mark</em></th>
      <th>Type :</th>
      <th colspan="2">Numéro de série :<br><em>Serial number</em></th>
      <th colspan="2">Puissance :<br><em>Power</em></th>
    </tr>
    <tr>
      <td >{{ $contrat->navire->marque_moteur }}</td>
      <td >{{ $contrat->navire->type_moteur }}</td>
      <td colspan="2">{{ $contrat->navire->numero_serie_moteur }}</td>
      <td colspan="2">{{ $contrat->navire->puissance_moteur }}</td>
    </tr>
  
    <!-- Mouvements -->
    <tr>
        <th colspan="2" rowspan="3" style="text-align: left;">
          Autres prestations : <br>Other services <br>(*)
        </th>
        <th>Guidage <br> Guidance</th>
        <th>Gardiennage <br> Boatcare</th>
        <th>Eau <br> Water </th>
        <th>Electricité <br> Electricity</th>
        <th>Douche <br>Shower</th>
      </tr>

      @php
      $prestations = $contrat->mouvements['autres_prestations'] ?? [];
    @endphp
      <tr>
        <td><input type="checkbox" name="autres_prestations[]" value="Guidage" {{ in_array('Guidage', $prestations) ? 'checked' : '' }}></td>
        <td><input type="checkbox" name="autres_prestations[]" value="Gardiennage" {{ in_array('Gardiennage', $prestations) ? 'checked' : '' }}></td>
        <td><input type="checkbox" name="autres_prestations[]" value="Eau" {{ in_array('Eau', $prestations) ? 'checked' : '' }}></td>
        <td><input type="checkbox" name="autres_prestations[]" value="Electricité" {{ in_array('Electricité', $prestations) ? 'checked' : '' }}></td>
        <td><input type="checkbox" name="autres_prestations[]" value="Douche" {{ in_array('Douche', $prestations) ? 'checked' : '' }}></td>
      </tr>
  
  </table>
  

<table>
  <tr>
    <th colspan="5" class="text-center">Emplacement et période facturé / Billing place and period :</th>

  </tr>
  <tr>
    <th colspan="2">Ponton / Pontoon :</th>
    <th>N° Poste / Dock n° :</th>
    <th>Date Début / <em>Start Date</em> :</th>
    <th>Date Fin / <em>End Date</em> :</th>
  </tr>
  <tr>
<td colspan="2" style="padding:1px 0px 1px 5px;">{{ $contrat->mouvements['ponton'] }}</td>
<td style="padding:1px 0px 1px 5px;">{{ $contrat->mouvements['num_poste'] }}</td>
<td style="padding:1px 0px 1px 5px;">{{ $contrat->date_debut }}</td>
<td style="padding:1px 0px 1px 5px;">{{ $contrat->date_fin }}</td>
  </tr>
  <tr>
    <th rowspan="2" style="text-align: left; vertical-align:center; padding:1px 0px 1px 5px;" >Le marin / Gardien / <em>Skipper / Watchman</em></th>
    <td colspan="4" style="padding:1px 0px 1px 5px;">Nom & Prénom : {{$contrat->gardien->nom}} <br> First & last name</td>
    
  </tr>
  <tr>
    <td colspan="2" style="padding:1px 0px 1px 5px;">N° de téléphone : {{$contrat->gardien->tel}}<br>Phone n°</td>
    <td colspan="2" style="padding:1px 0px 1px 5px;">CIN : {{$contrat->gardien->cin}} <br> Passeport</td>
  </tr>
</table>

<p style="font-size: 12px">
  <span class="checkbox"></span> J’accepte de me conformer aux clauses du présent contrat et aux règlements en vigueur relatifs à Bouregreg Marina<br>
  <span class="checkbox"></span> I agree to comply with the terms of this contract and the regulations in force relating to Bouregreg Marina
</p>

<table class="no-border-table">
    <tr class="no-border-tr">
      <td class="no-border-td">Signé par le propriétaire / demandeur :<br><em>Signed by the Owner / person in charge</em></td>
      <td class="no-border-td">Accepté par Bouregreg Marina<br><em>Accepted by Bouregreg Marina</em></td>
    </tr>
    <tr class="no-border-tr">
      <td class="no-border-td">À Salé / <em>At Salé</em> le : {{$contrat->date_signature}}</td>
      <td class="no-border-td">Le / <em>The</em> : {{$contrat->accepte_le}}</td>

    </tr>
    <tr class="no-border-tr">
      <td class="no-border-td">Par / <em>By</em> : {{$contrat->signe_par}}</td>
      <td class="no-border-td"></td>
    </tr>
  </table>
  

<p class="small">(*) : Conformément au cahier des tarifs en vigueur / <em>In accordance with the tariff book in force</em></p>

<div class="footer">
    Bouregreg Marina, siège social : Avenue de Fès Quartier Rmel Bab Lamrissa, Salé <br>
    Société Anonyme au capital de 20 000 000,00 dhs. ICE :1709000004 Patente N°25198370 <br>
    IF : N°03380237 - RC N°25785 à Salé, N° de compte :007 810 0001512000002340 79 Code Swift :BCMAMAMC <br>
    www.bouregregmarina.com Tél : 05 37 84 99 00 Fax : 05 37 78 58 58 - V04_20230105</div>
</body>
</html>