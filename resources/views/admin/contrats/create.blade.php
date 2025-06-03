<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            <i class="fas fa-file-contract text-primary-600 mr-3"></i>
            {{ __('Créer un contrat') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    
                    <!-- Success Message -->
                    <div id="success-message" class="hidden mb-6 p-4 bg-green-100 border-l-4 border-green-500 text-green-700 rounded-r-lg">
                        <i class="fas fa-check-circle mr-2"></i>
                        Contrat créé avec succès !
                    </div>

                    <form class="space-y-8" action="{{ route('contrat.store') }}" method="POST">
                        @csrf
                        <!-- Type de contrat -->
                        <div class="border-l-4 border-blue-500 pl-6 py-4 bg-blue-50/50 rounded-r-lg">
                            <h3 class="text-2xl font-bold text-gray-800 mb-6 flex items-center space-x-2">
                                <i class="fa-solid fa-file-contract text-blue-500"></i>
                                <span>Type de contrat</span>
                            </h3>
                            <select name="type_contrat" id="type_contrat"
                                class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:border-blue-500 focus:ring-4 focus:ring-blue-100 transition-all duration-200"
                                required onchange="typeContrat()">
                                <option value="0" selected>-- Selectionner le type de contrat --</option>
                                <option value="randonnee">Contrat Randonnee</option>
                                <option value="accostage">Contrat Accostage</option>
                            </select>
                        </div>
                        
                        <!-- Section Demandeur -->
                        <div class="border-l-4 border-blue-500 pl-6 py-4 bg-blue-50/50 rounded-r-lg">
                            <h3 class="text-2xl font-bold text-gray-800 mb-6 flex items-center">
                                <i class="fas fa-user text-blue-600 mr-3"></i>
                                Informations du Demandeur
                            </h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="space-y-4">
                                    <div>
                                        <x-input-label for="nom_demandeur" :value="__('Nom du demandeur')" />
                                        <x-text-input id="nom_demandeur" name="nom_demandeur" type="text" class="mt-1 block w-full" placeholder="Entrez le nom complet" />
                                    </div>
                                    <div>
                                        <x-input-label for="cin_pass_demandeur" :value="__('CIN / Passport')" />
                                        <x-text-input id="cin_pass_demandeur" name="cin_pass_demandeur" type="text" class="mt-1 block w-full" placeholder="Numéro CIN" />
                                    </div>
                                    <div>
                                        <x-input-label for="tel_demandeur" :value="__('Téléphone')" />
                                        <x-text-input id="tel_demandeur" name="tel_demandeur" type="tel" class="mt-1 block w-full" placeholder="+212 6XX XXX XXX" />
                                    </div>
                                </div>
                                <div class="space-y-4">
                                    <div>
                                        <x-input-label for="adresse_demandeur" :value="__('Adresse')" />
                                        <x-text-input id="adresse_demandeur" name="adresse_demandeur" type="text" class="mt-1 block w-full" placeholder="Adresse complète" />
                                    </div>
                                    <div>
                                        <x-input-label for="email_demandeur" :value="__('Email')" />
                                        <x-text-input id="email_demandeur" name="email_demandeur" type="email" class="mt-1 block w-full" placeholder="exemple@email.com" />
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Section Propriétaire -->
                        <div class="border-l-4 border-green-500 pl-6 py-4 bg-green-50/50 rounded-r-lg">
                            <h3 class="text-2xl font-bold text-gray-800 mb-6 flex items-center">
                                <i class="fas fa-building text-green-600 mr-3"></i>
                                Informations du Propriétaire
                            </h3>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
                                <div>
                                    <h2 class="text-center text-xl font-bold text-gray-700 mb-6">Propriétaire Physique</h2>
                                    <div class="space-y-4">
                                        <div>
                                            <x-input-label for="nom_proprietaire" :value="__('Nom & Prénom du propriétaire')" />
                                            <x-text-input id="nom_proprietaire" name="nom_proprietaire" type="text" class="mt-1 block w-full" placeholder="Nom du propriétaire" />
                                        </div>
                                        <div>
                                            <x-input-label for="tel_proprietaire" :value="__('Numéro de téléphone')" />
                                            <x-text-input id="tel_proprietaire" name="tel_proprietaire" type="tel" class="mt-1 block w-full" placeholder="+212 6XX XXX XXX" />
                                        </div>
                                        <div>
                                            <x-input-label for="nationalite_proprietaire" :value="__('Nationalité')" />
                                            <x-text-input id="nationalite_proprietaire" name="nationalite_proprietaire" type="text" class="mt-1 block w-full" placeholder="Nationalité" />
                                        </div>
                                        <div>
                                            <x-input-label for="cin_pass_proprietaire" :value="__('N° CIN (ou Passeport)')" />
                                            <x-text-input id="cin_pass_proprietaire" name="cin_pass_proprietaire" type="text" class="mt-1 block w-full" placeholder="Numéro CIN" />
                                        </div>
                                        <div>
                                            <x-input-label for="validite_cin" :value="__('Validité jusqu\'à')" />
                                            <x-text-input id="validite_cin" name="validite_cin" type="date" class="mt-1 block w-full" />
                                        </div>
                                    </div>
                                </div>
                                
                                <div>
                                    <h2 class="text-center text-xl font-bold text-gray-700 mb-6">Propriétaire Morale</h2>
                                    <div class="space-y-4">
                                        <div>
                                            <x-input-label for="nom_societe" :value="__('Nom de la société')" />
                                            <x-text-input id="nom_societe" name="nom_societe" type="text" class="mt-1 block w-full" placeholder="Nom de la société (si applicable)" />
                                        </div>
                                        <div>
                                            <x-input-label for="ice" :value="__('ICE')" />
                                            <x-text-input id="ice" name="ice" type="text" class="mt-1 block w-full" placeholder="Identifiant Commun de l'Entreprise" />
                                        </div>
                                        <div>
                                            <x-input-label for="caution_solidaire" :value="__('Caution personnelle solidaire')" />
                                            <x-text-input id="caution_solidaire" name="caution_solidaire" type="text" class="mt-1 block w-full" placeholder="Caution solidaire" />
                                        </div>
                                        <div>
                                            <x-input-label for="cin_pass_proprietaire_morale" :value="__('N° CIN (ou Passeport)')" />
                                            <x-text-input id="cin_pass_proprietaire_morale" name="cin_pass_proprietaire_morale" type="text" class="mt-1 block w-full" placeholder="Numéro de passeport" />
                                        </div>
                                        <div>
                                            <x-input-label for="num_police" :value="__('N° de Police')" />
                                            <x-text-input id="num_police" name="num_police" type="text" class="mt-1 block w-full" placeholder="Numéro de police" />
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="accostage-section hidden mt-6 space-y-4">
                                <div>
                                    <x-input-label for="com_assurance" :value="__('Compagnie d\'Assurance')" />
                                    <x-text-input id="com_assurance" name="com_assurance" type="text" class="mt-1 block w-full" placeholder="Compagnie d'Assurance" />
                                </div>
                                <div>
                                    <x-input-label for="num_police_assurance" :value="__('N° de Police')" />
                                    <x-text-input id="num_police_assurance" name="num_police_assurance" type="text" class="mt-1 block w-full" placeholder="Numéro de police" />
                                </div>
                                <div>                   
                                    <x-input-label for="echeance" :value="__('Échéance')" />
                                    <x-text-input id="echeance" name="echeance" type="text" class="mt-1 block w-full" placeholder="Échéance" />
                                </div>
                            </div>
                        </div>

                        <!-- Section Navire -->
                        <div class="border-l-4 border-purple-500 pl-6 py-4 bg-purple-50/50 rounded-r-lg">
                            <h3 class="text-2xl font-bold text-gray-800 mb-6 flex items-center">
                                <i class="fas fa-ship text-purple-600 mr-3"></i>
                                Informations du Navire
                            </h3>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                <div class="space-y-4">
                                    <div>
                                        <x-input-label for="nom_navire" :value="__('Nom du navire')" />
                                        <x-text-input id="nom_navire" name="nom_navire" type="text" class="mt-1 block w-full" placeholder="Nom du navire" />
                                    </div>
                                    <div>
                                        <x-input-label for="type_navire" :value="__('Type de navire')" />
                                        <x-text-input id="type_navire" name="type_navire" type="text" class="mt-1 block w-full" placeholder="Type de navire" />
                                    </div>
                                    <div>
                                        <x-input-label for="pavillon" :value="__('Pavillon')" />
                                        <x-text-input id="pavillon" name="pavillon" type="text" class="mt-1 block w-full" placeholder="Pavillon" />
                                    </div>
                                    <div>
                                        <x-input-label for="longueur" :value="__('Longueur (m)')" />
                                        <x-text-input id="longueur" name="longueur" type="number" class="mt-1 block w-full" placeholder="Longueur en mètres" step="0.01" />
                                    </div>
                                    <div>
                                        <x-input-label for="largeur" :value="__('Largeur (m)')" />
                                        <x-text-input id="largeur" name="largeur" type="number" class="mt-1 block w-full" placeholder="Largeur en mètres" step="0.01" />
                                    </div>
                                </div>
                                <div class="space-y-4">
                                    <div>
                                        <x-input-label for="tirant_eau" :value="__('Tirant d\'eau (m)')" />
                                        <x-text-input id="tirant_eau" name="tirant_eau" type="number" class="mt-1 block w-full" placeholder="Tirant d'eau" step="0.01" />
                                    </div>
                                    <div>
                                        <x-input-label for="tirant_air" :value="__('Tirant d\'air (m)')" />
                                        <x-text-input id="tirant_air" name="tirant_air" type="number" class="mt-1 block w-full" placeholder="Tirant d'air" step="0.01" />
                                    </div>
                                    <div>
                                        <x-input-label for="jauge_brute" :value="__('Jauge brute')" />
                                        <x-text-input id="jauge_brute" name="jauge_brute" type="number" class="mt-1 block w-full" placeholder="Jauge brute" />
                                    </div>
                                    <div>
                                        <x-input-label for="annee_construction" :value="__('Année de construction')" />
                                        <x-text-input id="annee_construction" name="annee_construction" type="number" class="mt-1 block w-full" placeholder="Année" min="1900" max="2024" />
                                    </div>
                                </div>
                                <div class="space-y-4">
                                    <div>
                                        <h2 class="text-center text-xl font-bold text-gray-700 mb-6">Immatriculation</h2>
                                    </div>
                                    <div>
                                        <x-input-label for="port" :value="__('Port')" />
                                        <x-text-input id="port" name="port" type="text" class="mt-1 block w-full" placeholder="Port d'attache" />
                                    </div>
                                    <div>
                                        <x-input-label for="numero_immatriculation" :value="__('Numéro d\'immatriculation')" />
                                        <x-text-input id="numero_immatriculation" name="numero_immatriculation" type="text" class="mt-1 block w-full" placeholder="Numéro d'immatriculation" />
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Section Moteur -->
                        <div class="border-l-4 border-orange-500 pl-6 py-4 bg-orange-50/50 rounded-r-lg">
                            <h3 class="text-2xl font-bold text-gray-800 mb-6 flex items-center">
                                <i class="fas fa-cog text-orange-600 mr-3"></i>
                                Informations du Moteur
                            </h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <x-input-label for="marque_moteur" :value="__('Marque moteur')" />
                                    <x-text-input id="marque_moteur" name="marque_moteur" type="text" class="mt-1 block w-full" placeholder="Marque du moteur" />
                                </div>
                                <div>
                                    <x-input-label for="type_moteur" :value="__('Type moteur')" />
                                    <x-text-input id="type_moteur" name="type_moteur" type="text" class="mt-1 block w-full" placeholder="Type de moteur" />
                                </div>
                                <div>
                                    <x-input-label for="numero_serie_moteur" :value="__('Numéro de série moteur')" />
                                    <x-text-input id="numero_serie_moteur" name="numero_serie_moteur" type="text" class="mt-1 block w-full" placeholder="Numéro de série" />
                                </div>
                                <div>
                                    <x-input-label for="puissance_moteur" :value="__('Puissance moteur (CV)')" />
                                    <x-text-input id="puissance_moteur" name="puissance_moteur" type="number" class="mt-1 block w-full" placeholder="Puissance en CV" />
                                </div>
                            </div>
                        </div>

                        <!-- Section Autres prestations (Accostage) -->
                        <div class="accostage-section hidden border-l-4 border-slate-600 pl-6 py-4 bg-slate-50/50 rounded-r-lg">
                            <h3 class="text-2xl font-bold text-gray-800 mb-6 flex items-center">
                                <i class="fa-solid fa-check-double text-slate-600 mr-3"></i>
                                Autres prestations
                            </h3>
                            
                            <div class="overflow-x-auto">
                                <table class="min-w-full border text-center">
                                    <thead>
                                        <tr class="bg-gray-100 text-sm">
                                            <th rowspan="2" class="border px-4 py-2 w-1/3"> 
                                                <span class="text-xs italic text-gray-600">Autres prestations :</span><br/>    
                                            </th>
                                            <th class="border px-4 py-2">Guidage</th>
                                            <th class="border px-4 py-2">Gardiennage</th>
                                            <th class="border px-4 py-2">Eau</th>
                                            <th class="border px-4 py-2">Electricité</th>
                                            <th class="border px-4 py-2">Douche</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td class="border px-4 py-2 font-medium text-left">Choisir une option :</td>
                                            <td class="border px-4 py-2">
                                                <input type="checkbox" name="autres_prestations[]" value="Guidage" class="w-6 h-6" checked />
                                            </td>
                                            <td class="border px-4 py-2">
                                                <input type="checkbox" name="autres_prestations[]" value="Gardiennage" class="w-6 h-6" />
                                            </td>
                                            <td class="border px-4 py-2">
                                                <input type="checkbox" name="autres_prestations[]" value="Eau" class="w-6 h-6" />
                                            </td>
                                            <td class="border px-4 py-2">
                                                <input type="checkbox" name="autres_prestations[]" value="Electricité" class="w-6 h-6" />
                                            </td>
                                            <td class="border px-4 py-2">
                                                <input type="checkbox" name="autres_prestations[]" value="Douche" class="w-6 h-6" />
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Section Mouvements (Randonnée) -->
                        <div class="randonnee-section hidden border-l-4 border-teal-500 pl-6 py-4 bg-teal-50/50 rounded-r-lg">
                            <h3 class="text-2xl font-bold text-gray-800 mb-6 flex items-center">
                                <i class="fas fa-route text-teal-600 mr-3"></i>
                                Mouvements et Tarification
                            </h3>
                        
                            <div class="overflow-x-auto">
                                <table class="min-w-full border text-center">
                                    <thead>
                                        <tr class="bg-gray-100 text-sm">
                                            <th rowspan="2" class="border px-4 py-2 w-1/3">
                                                Mouvements par marée<br/>
                                                <span class="text-xs italic text-gray-600">Movements per tide</span><br/><br/>
                                                Majoration des frais de stationnement<br/>
                                                <span class="text-xs italic text-gray-600">Increased docking fee (*)</span>
                                            </th>
                                            <th class="border px-4 py-2">1 seul mouvement</th>
                                            <th class="border px-4 py-2">2 mouvements</th>
                                            <th class="border px-4 py-2">Mouvements pleine journée</th>
                                        </tr>
                                        <tr class="bg-gray-50 text-sm">
                                            <td class="border px-4 py-2">+25%</td>
                                            <td class="border px-4 py-2">+50%</td>
                                            <td class="border px-4 py-2">+100%</td>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td class="border px-4 py-2 font-medium text-left">Choisir une option :</td>
                                            <td class="border px-4 py-2">
                                                <input type="radio" name="majoration_stationnement" value="25" class="w-6 h-6" checked />
                                            </td>
                                            <td class="border px-4 py-2">
                                                <input type="radio" name="majoration_stationnement" value="50" class="w-6 h-6" />
                                            </td>
                                            <td class="border px-4 py-2">
                                                <input type="radio" name="majoration_stationnement" value="100" class="w-6 h-6" />
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Section Emplacement (Accostage) -->
                        <div class="accostage-section hidden border-l-4 border-red-500 pl-6 py-4 bg-red-50/50 rounded-r-lg">
                            <h3 class="text-2xl font-bold text-gray-800 mb-6 flex items-center">
                                <i class="fa-solid fa-location-dot text-red-600 mr-3"></i>
                                Emplacement
                            </h3>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                <div>
                                    <x-input-label for="ponton" :value="__('Ponton')" />
                                    <x-text-input id="ponton" name="ponton" type="text" class="mt-1 block w-full" placeholder="Ponton" />
                                </div>
                                <div>
                                    <x-input-label for="num_poste" :value="__('N° Poste')" />
                                    <x-text-input id="num_poste" name="num_poste" type="text" class="mt-1 block w-full" placeholder="N° Poste" />
                                </div>
                            </div>
                        </div>

                        <!-- Section Équipage (Randonnée) -->
                        <div class="randonnee-section hidden border-l-4 border-red-500 pl-6 py-4 bg-red-50/50 rounded-r-lg">
                            <h3 class="text-2xl font-bold text-gray-800 mb-6 flex items-center">
                                <i class="fas fa-users text-red-600 mr-3"></i>
                                Équipage et Passagers
                            </h3>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                <div>
                                    <x-input-label for="equipage" :value="__('Équipage')" />
                                    <x-text-input id="equipage" name="equipage" type="number" class="mt-1 block w-full" placeholder="Nombre d'équipiers" min="0" />
                                </div>
                                <div>
                                    <x-input-label for="passagers" :value="__('Passagers')" />
                                    <x-text-input id="passagers" name="passagers" type="number" class="mt-1 block w-full" placeholder="Nombre de passagers" min="0" />
                                </div>
                                <div>
                                    <x-input-label for="total_personnes" :value="__('Total personnes')" />
                                    <x-text-input id="total_personnes" name="total_personnes" type="number" class="mt-1 block w-full bg-gray-50" placeholder="Total automatique" readonly />
                                </div>
                            </div>
                        </div>

                        <!-- Section Dates -->
                        <div class="border-l-4 border-indigo-500 pl-6 py-4 bg-indigo-50/50 rounded-r-lg">
                            <h3 class="text-2xl font-bold text-gray-800 mb-6 flex items-center">
                                <i class="fas fa-calendar-alt text-indigo-600 mr-3"></i>
                                Période facturé
                            </h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <x-input-label for="date_debut" :value="__('Date de début')" />
                                    <x-text-input id="date_debut" name="date_debut" type="date" class="mt-1 block w-full" />
                                </div>
                                <div>
                                    <x-input-label for="date_fin" :value="__('Date de fin')" />
                                    <x-text-input id="date_fin" name="date_fin" type="date" class="mt-1 block w-full" />
                                </div>
                            </div>
                        </div>

                        <!-- Section Gardien -->
                        <div class="border-l-4 border-yellow-500 pl-6 py-4 bg-yellow-50/50 rounded-r-lg">
                            <h3 class="text-2xl font-bold text-gray-800 mb-6 flex items-center">
                                <i class="fas fa-shield-alt text-yellow-600 mr-3"></i>
                                Informations du Gardien
                            </h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="space-y-4">
                                    <div>
                                        <x-input-label for="nom_gardien" :value="__('Nom & Prénom')" />
                                        <x-text-input id="nom_gardien" name="nom_gardien" type="text" class="mt-1 block w-full" placeholder="Nom complet du gardien" />
                                    </div>
                                    <div>
                                        <x-input-label for="num_tele_gardien" :value="__('N° de téléphone / Passport')" />
                                        <x-text-input id="num_tele_gardien" name="num_tele_gardien" type="text" class="mt-1 block w-full" placeholder="N° de téléphone" />
                                    </div>
                                    <div>
                                        <x-input-label for="cin_gardien" :value="__('CIN gardien')" />
                                        <x-text-input id="cin_gardien" name="cin_gardien" type="text" class="mt-1 block w-full" placeholder="Numéro CIN" />
                                    </div>
                                </div>
                                <div class="space-y-4">
                                    <div>
                                        <x-input-label for="tel_gardien" :value="__('Téléphone gardien')" />
                                        <x-text-input id="tel_gardien" name="tel_gardien" type="tel" class="mt-1 block w-full" placeholder="+212 6XX XXX XXX" />
                                    </div>
                                    <div>
                                        <x-input-label for="passeport_gardien" :value="__('Passeport gardien')" />
                                        <x-text-input id="passeport_gardien" name="passeport_gardien" type="text" class="mt-1 block w-full" placeholder="Numéro de passeport" />
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Section Validation -->
                        <div class="border-l-4 border-pink-500 pl-6 py-4 bg-pink-50/50 rounded-r-lg">
                            <h3 class="text-2xl font-bold text-gray-800 mb-6 flex items-center">
                                <i class="fas fa-pen-fancy text-pink-600 mr-3"></i>
                                Validation et Signature
                            </h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="space-y-4">
                                    <div>
                                        <label class="block text-sm font-semibold text-gray-700 mb-2">Signé par</label>
                                        <select name="signe_par" class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:border-blue-500 focus:ring-4 focus:ring-blue-100 transition-all duration-200">
                                            <option value="">Sélectionnez le signataire</option>
                                            <option value="demandeur">Demandeur</option>
                                            <option value="proprietaire">Propriétaire</option>
                                            <option value="gardien">Gardien</option>
                                        </select>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-semibold text-gray-700 mb-2">Date de signature</label>
                                        <input type="date" name="date_signature" class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:border-blue-500 focus:ring-4 focus:ring-blue-100 transition-all duration-200">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Boutons de soumission -->
                        <div class="mt-8 flex justify-end space-x-4">
                            <button type="button" class="px-6 py-3 border-2 border-gray-300 text-gray-700 font-semibold rounded-lg hover:bg-gray-50 transition-all duration-200">
                                <i class="fas fa-times mr-2"></i>
                                Annuler
                            </button>
                            <button type="submit" disabled class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-6 py-3 rounded-lg shadow-lg transition-all duration-200 disabled:opacity-50 disabled:cursor-not-allowed" id="btnsubmit">
                                <i class="fas fa-paper-plane mr-2"></i>
                                Créer le contrat
                            </button>
                        </div>
                    </form>
                    </div>
                </div>
            </div>
        </div>

    <script>
        function typeContrat() {
        const contratType = document.getElementById("type_contrat").value;
        const accostageSections = document.querySelectorAll(".accostage-section");
        const randonneeSections = document.querySelectorAll(".randonnee-section");
        const btnsubmit = document.getElementById('btnsubmit');

        const hideSection = (sections) => {
        sections.forEach(section => {
        section.classList.add('hidden');
        section.querySelectorAll("input, select, textarea").forEach(field => {
        field.disabled = true;
        });
        });
        };

        const showSection = (sections) => {
        sections.forEach(section => {
        section.classList.remove('hidden');
        section.querySelectorAll("input, select, textarea").forEach(field => {
        field.disabled = false;
        });
        });
        };

        hideSection(accostageSections);
        hideSection(randonneeSections);
        btnsubmit.disabled = true;

        if (contratType === "accostage") {
        showSection(accostageSections);
        btnsubmit.disabled = false;
        } else if (contratType === "randonnee") {
        showSection(randonneeSections);
        btnsubmit.disabled = false;
        }
        }

        document.addEventListener('DOMContentLoaded', function() {
        const equipageInput = document.querySelector('input[name="equipage"]');
        const passagersInput = document.querySelector('input[name="passagers"]');
        const totalInput = document.querySelector('input[name="total_personnes"]');

        function updateTotal() {
        const equipage = parseInt(equipageInput.value) || 0;
        const passagers = parseInt(passagersInput.value) || 0;
        totalInput.value = equipage + passagers;
        }

        if (equipageInput && passagersInput && totalInput) {
        equipageInput.addEventListener('input', updateTotal);
        passagersInput.addEventListener('input', updateTotal);
        }
        });
    </script>
</x-app-layout>