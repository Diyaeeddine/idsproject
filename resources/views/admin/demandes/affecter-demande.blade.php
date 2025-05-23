<x-app-layout>
    <x-slot name="header">
        <div class='flex justify-between items-center'>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Affectation des demandes') }}
            </h2>
            <a href="{{ route('demande.add-demande') }}" class="inline-flex items-center px-4 py-2 bg-gray-100 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md font-semibold text-xs text-gray-700 dark:text-gray-300 uppercase tracking-widest shadow-sm hover:bg-gray-200 dark:hover:bg-gray-600 transition">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                {{ __('Retour') }}
            </a>
        </div>
        @if (session('success'))
            <div class="bg-green-50 dark:bg-green-900/50 text-green-800 dark:text-green-300 p-4 mb-6 rounded-md mt-4">
                {{ session('success') }}
            </div>
        @endif
        @if (session('error'))
            <div class="bg-red-50 dark:bg-red-900/50 text-red-800 dark:text-red-300 p-4 mb-6 rounded-md mt-4">
                {{ session('error') }}
            </div>
        @endif
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="flex flex-col md:flex-row">
                    <!-- Sidebar - Liste des demandes -->
                    <div class="w-full md:w-1/4 bg-gray-50 dark:bg-gray-900 border-r border-gray-200 dark:border-gray-700 p-4">
                        <h2 class="text-lg font-semibold mb-4 text-gray-800 dark:text-gray-200">{{ __('Formulaires') }}</h2>
                        
                        <nav class="space-y-1">
                            @php
                                $demandesList = $demandes ?? \App\Models\Demande::with('users')->latest()->get();
                                if (!isset($selectedDemande)) {
                                    $id = request()->route('id') ?? ($demandesList->first()->id ?? null);
                                    $selectedDemande = $id ? \App\Models\Demande::with('users')->find($id) : null;
                                }
                            @endphp

                            @forelse($demandesList as $d)
                                <a href="{{ route('demandes.affecter', $d->id) }}"
                                   class="flex justify-between items-center px-3 py-2 rounded-md text-sm transition-colors
                                          {{ $selectedDemande && $selectedDemande->id === $d->id
                                              ? 'bg-indigo-50 text-indigo-700 dark:bg-indigo-900 dark:text-indigo-200 border-l-4 border-indigo-500'
                                              : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800' }}">
                                    <div class="truncate">
                                        <span class="font-medium">{{ $d->titre }}</span>
                                        <span class="block text-xs text-gray-500 dark:text-gray-400 truncate">
                                            Créé le {{ $d->created_at->format('d/m/Y') }}
                                        </span>
                                    </div>
                                </a>
                            @empty
                                <div class="text-sm text-gray-500 dark:text-gray-400 italic text-center py-4">
                                    {{ __('Aucune demande disponible') }}
                                </div>
                            @endforelse
                        </nav>
                    </div>

                    <!-- Main content -->
                    <div class="w-full md:w-3/4 p-6">
                        @if($selectedDemande)
                            <div class="flex items-center justify-between mb-6">
                                <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-200">
                                    {{ $selectedDemande->titre }}
                                </h2>
                                <span class="px-2 py-1 text-xs rounded-full {{ 
                                    $selectedDemande->statut === 'en_attente' ? 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200' : 
                                    ($selectedDemande->statut === 'affecte' ? 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200' : 
                                    'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200') 
                                }}">
                                    {{ 
                                        $selectedDemande->statut === 'en_attente' ? 'En attente' : 
                                        ($selectedDemande->statut === 'affecte' ? 'Affecté' : 
                                        ($selectedDemande->statut === 'partiellement_affecte' ? 'Partiellement affecté' : 'Traité')) 
                                    }}
                                </span>
                            </div>

                            <div class="bg-gray-50 dark:bg-gray-900 p-4 rounded-lg mb-6">
                                <div class="flex flex-wrap text-sm text-gray-600 dark:text-gray-400">
                                    <div class="w-full sm:w-1/2 mb-3">
                                        <span class="block font-medium">Date de demande :</span>
                                        {{ \Carbon\Carbon::parse($selectedDemande->created_at)->format('d/m/Y H:i') }}
                                    </div>
                                </div>
                            </div>

                            <form method="POST" action="{{ route('demande.affecterChamps', $selectedDemande->id) }}" id="affectation-form">
                                @csrf
                                
                                <!-- Section d'affectation -->
                                <div class="bg-white dark:bg-gray-700 p-6 rounded-lg border border-gray-200 dark:border-gray-600 mb-6">
                                    <h3 class="text-lg font-medium text-gray-800 dark:text-gray-200 mb-4">
                                        Affectation des champs
                                    </h3>

                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                        <!-- Sélection utilisateur -->
                                        <div>
                                            <label for="user_id" class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-300">
                                                Affecter à l'utilisateur :
                                            </label>
                                            <select name="user_id" id="user_id" class="w-full rounded-lg border-gray-300 dark:bg-gray-800 dark:text-gray-200 dark:border-gray-600 focus:ring-indigo-500 focus:border-indigo-500" required>
                                                <option value="">-- Sélectionner un utilisateur --</option>
                                                @foreach ($users as $user)
                                                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>

                
                                    </div>
                                </div>

                                <!-- Tableau des champs -->
                                <div class="bg-white dark:bg-gray-700 overflow-hidden border border-gray-200 dark:border-gray-600 sm:rounded-lg">
                                    <div class="px-6 py-4 bg-gray-50 dark:bg-gray-800 border-b border-gray-200 dark:border-gray-600">
                                        <h3 class="text-lg font-medium text-gray-800 dark:text-gray-200">
                                            Champs du formulaire

                                        </h3>
                                    </div>
                                    
                                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-600">
                                        <thead class="bg-gray-50 dark:bg-gray-800">
                                            <tr>
                                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider w-16">
                                                </th>
                                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Clé</th>
                                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Valeur actuelle</th>
                                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Statut</th>
                                            </tr>
                                        </thead>
                                        <tbody class="bg-white dark:bg-gray-700 divide-y divide-gray-200 dark:divide-gray-600">
                                            @foreach($selectedDemande->champs as $champ)
                                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-600 transition-colors champ-row">
                                                    <td class="px-6 py-4 whitespace-nowrap">
                                                        <input type="checkbox" 
                                                               name="champs_selected[]" 
                                                               value="{{ $champ->id }}" 
                                                               class="champ-checkbox rounded border-gray-300 text-indigo-600">
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap">
                                                        <div class="text-sm font-medium text-gray-900 dark:text-gray-200">
                                                            {{ $champ->key }}
                                                        </div>
                                                    </td>
                                                    <td class="px-6 py-4">
                                                        <div class="text-sm text-gray-500 dark:text-gray-300">
                                                            @if($champ->value)
                                                                <span class="px-2 py-1 bg-gray-100 dark:bg-gray-600 rounded text-xs">{{ Str::limit($champ->value, 50) }}</span>
                                                            @else
                                                                <span class="text-gray-400 italic">Vide</span>
                                                            @endif
                                                        </div>
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap">
                                                        @if($champ->user_id)
                                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                                                                Affecté
                                                            </span>
                                                        @else
                                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200">
                                                                En attente
                                                            </span>
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>

                                <!-- Boutons d'action -->
                                <div class="mt-6 flex justify-end">
                                    <button type="submit" id="submit-btn" class="px-6 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg transition-colors disabled:opacity-50 disabled:cursor-not-allowed" disabled>
                                        Affecter les champs sélectionnés
                                    </button>
                                </div>
                            </form>
                            
                        @else
                            <div class="flex flex-col items-center justify-center h-96 text-center">
                                <svg class="w-16 h-16 text-gray-400 dark:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                                <p class="mt-2 text-lg font-medium text-gray-600 dark:text-gray-400">Sélectionnez un formulaire</p>
                                <p class="text-sm text-gray-500 dark:text-gray-500">Choisissez un formulaire dans la liste à gauche pour voir les détails et l'affecter à un utilisateur.</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const userSelect = document.getElementById('user_id');
            const champCheckboxes = document.querySelectorAll('.champ-checkbox');
            const selectAllCheckbox = document.getElementById('');
            const selectAllBtn = document.getElementById('select-all');
            const deselectAllBtn = document.getElementById('deselect-all');
            const submitBtn = document.getElementById('submit-btn');
            const form = document.getElementById('affectation-form');

            function updateUI() {
                const selectedCheckboxes = document.querySelectorAll('.champ-checkbox:checked');
                const selectedCount = selectedCheckboxes.length;
                const totalCount = champCheckboxes.length;
                const userSelected = userSelect.value !== '';

                // Activer/désactiver le bouton submit
                submitBtn.disabled = !(selectedCount > 0 && userSelected);

                // Gérer l'état du checkbox "Tout sélectionner"

                // Mettre à jour les classes des lignes
                champCheckboxes.forEach(checkbox => {
                    const row = checkbox.closest('.champ-row');
                    if (checkbox.checked) {
                        row.classList.add('bg-indigo-50', 'dark:bg-indigo-900/20');
                    } else {
                        row.classList.remove('bg-indigo-50', 'dark:bg-indigo-900/20');
                    }
                });
            }

            // Événements
            champCheckboxes.forEach(checkbox => {
                checkbox.addEventListener('change', updateUI);
            });

            userSelect.addEventListener('change', updateUI);

            selectAllCheckbox.addEventListener('change', function() {
                champCheckboxes.forEach(checkbox => {
                    checkbox.checked = this.checked;
                });
                updateUI();
            });

            selectAllBtn.addEventListener('click', function() {
                champCheckboxes.forEach(checkbox => {
                    checkbox.checked = true;
                });
                updateUI();
            });

            deselectAllBtn.addEventListener('click', function() {
                champCheckboxes.forEach(checkbox => {
                    checkbox.checked = false;
                });
                updateUI();
            });

            // Validation du formulaire
            form.addEventListener('submit', function(e) {
                const selectedCheckboxes = document.querySelectorAll('.champ-checkbox:checked');
                
                if (selectedCheckboxes.length === 0) {
                    e.preventDefault();
                    alert('Veuillez sélectionner au moins un champ à affecter.');
                    return;
                }

                if (!userSelect.value) {
                    e.preventDefault();
                    alert('Veuillez sélectionner un utilisateur.');
                    return;
                }

                // Confirmation
                const userName = userSelect.options[userSelect.selectedIndex].text;
                const confirmMessage = `Êtes-vous sûr de vouloir affecter ${selectedCheckboxes.length} champ(s) à ${userName} ?`;
                
                if (!confirm(confirmMessage)) {
                    e.preventDefault();
                }
            });

            // Initialiser l'UI
            updateUI();
        });
    </script>
</x-app-layout>