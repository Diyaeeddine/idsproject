<x-app-layout>
    <x-slot name="header">
        <div class='flex justify-between items-center'>
        
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Affectation des demandes') }}
        </h2>
        <a href="{{ route('demande.add-demande') }}" class="inline-flex items-center px-4 py-2 bg-gray-100 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md font-semibold text-xs text-gray-700 dark:text-gray-300 uppercase tracking-widest shadow-sm hover:bg-gray-200 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            {{ __('Retour') }}
          </a>
          </div>
        @if (session('success'))
        <div class="bg-green-50 dark:bg-green-900/50 text-green-800 dark:text-green-300 p-4 mb-6 rounded-md">
            {{ session('success') }}
        </div>
    @endif
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="flex flex-col md:flex-row">
                    {{-- Sidebar --}}
                    <div class="w-full md:w-1/4 bg-gray-50 dark:bg-gray-900 border-r border-gray-200 dark:border-gray-700 p-4 md:h-screen md:overflow-auto">
                        <h2 class="text-lg font-semibold mb-4 text-gray-800 dark:text-gray-200">{{ __('Formulaires') }}</h2>

                        <nav class="space-y-1 overflow-y-auto max-h-[500px] pr-1" id="demandes-list">
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
                                    @if($d->created_at->isToday())
                                        <span class="text-xs px-2 py-1 rounded-full bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                                            Aujourd'hui
                                        </span>
                                    @endif
                                </a>
                            @empty
                                <div class="text-sm text-gray-500 dark:text-gray-400 italic text-center py-4">
                                    {{ __('Aucune demande disponible') }}
                                </div>
                            @endforelse
                        </nav>
                    </div>

                    {{-- Content --}}
                    <div class="w-full md:w-3/4 p-6">
                        {{-- Élément caché pour stocker les données JSON de session --}}
                        <div id="last-updated-data" data-value="{{ json_encode(session('lastUpdatedAt', null)) }}" class="hidden"></div>
                        
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
                                        ($selectedDemande->statut === 'affecte' ? 'Affecté' : 'Traité') 
                                    }}
                                </span>
                            </div>

                            <div class="bg-gray-50 dark:bg-gray-900 p-4 rounded-lg mb-6">
                                <div class="flex flex-wrap text-sm text-gray-600 dark:text-gray-400">
                                    <div class="w-full sm:w-1/2 md:w-1/3 lg:w-1/4 mb-3">
                                        <span class="block font-medium">Demandeur :</span>
                                        {{ $selectedDemande->user->name ?? 'N/A' }}
                                    </div>
                                    <div class="w-full sm:w-1/2 md:w-1/3 lg:w-1/4 mb-3">
                                        <span class="block font-medium">Date de demande :</span>
                                        {{ \Carbon\Carbon::parse($selectedDemande->created_at)->format('d/m/Y H:i') }}
                                    </div>
                                    <div class="w-full sm:w-1/2 md:w-1/3 lg:w-1/4 mb-3">
                                        {{-- Conteneur pour le minuteur ou le statut --}}
                                        <div id="timer-container" class="text-sm"></div>
                                    </div>
                                </div>
                            </div>

                            {{-- Notification Toast pour le minuteur (invisible par défaut) --}}
                            <div id="toast-default" class="hidden fixed bottom-5 right-5 flex items-center w-full max-w-xs p-4 text-red-700 bg-red-100 rounded-lg shadow-sm dark:text-red-400 dark:bg-red-900" role="alert" aria-live="assertive" aria-atomic="true">
                                <div class="inline-flex items-center justify-center shrink-0 w-8 h-8 text-red-600 bg-red-200 rounded-lg dark:bg-red-800 dark:text-red-200">
                                    <svg class="w-4 h-4" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 18 20">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.147 15.085a7.159 7.159 0 0 1-6.189 3.307A6.713 6.713 0 0 1 3.1 15.444c-2.679-4.513.287-8.737.888-9.548A4.373 4.373 0 0 0 5 1.608c1.287.953 6.445 3.218 5.537 10.5 1.5-1.122 2.706-3.01 2.853-6.14 1.433 1.049 3.993 5.395 1.757 9.117Z"/>
                                    </svg>
                                    <span class="sr-only">Warning icon</span>
                                </div>
                                <div class="ms-3 text-sm font-normal">⚠ Attention : 10 secondes écoulées depuis la dernière affectation !</div>
                                <button type="button" id="toast-close-btn" class="ms-auto -mx-1.5 -my-1.5 bg-white text-red-400 hover:text-red-900 rounded-lg focus:ring-2 focus:ring-red-300 p-1.5 hover:bg-red-100 inline-flex items-center justify-center h-8 w-8 dark:text-red-500 dark:hover:text-white dark:bg-red-800 dark:hover:bg-red-700" aria-label="Close">
                                    <span class="sr-only">Close</span>
                                    <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                                    </svg>
                                </button>
                            </div>

                            {{-- Notification Toast pour formulaire complet (invisible par défaut) --}}
                            <div id="toast-success" class="hidden fixed bottom-5 right-5 flex items-center w-full max-w-xs p-4 text-green-700 bg-green-100 rounded-lg shadow-sm dark:text-green-400 dark:bg-green-900" role="alert" aria-live="assertive" aria-atomic="true">
                                <div class="inline-flex items-center justify-center shrink-0 w-8 h-8 text-green-600 bg-green-200 rounded-lg dark:bg-green-800 dark:text-green-200">
                                    <svg class="w-4 h-4" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 10l3 3 6-6M3 10a7 7 0 1014 0 7 7 0 00-14 0z"/>
                                    </svg>
                                    <span class="sr-only">Check icon</span>
                                </div>
                                <div class="ms-3 text-sm font-normal">✓ Tous les champs ont été remplis. Le formulaire est complet !</div>
                                <button type="button" id="toast-success-close-btn" class="ms-auto -mx-1.5 -my-1.5 bg-white text-green-400 hover:text-green-900 rounded-lg focus:ring-2 focus:ring-green-300 p-1.5 hover:bg-green-100 inline-flex items-center justify-center h-8 w-8 dark:text-green-500 dark:hover:text-white dark:bg-green-800 dark:hover:bg-green-700" aria-label="Close">
                                    <span class="sr-only">Close</span>
                                    <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                                    </svg>
                                </button>
                            </div>

                            <form method="POST" action="{{ route('demande.affecterChamps', $selectedDemande->id) }}">
                                @method('POST')
                                @csrf
                                
                                {{-- Champ caché pour indiquer si le formulaire est complet --}}
                                <input type="hidden" id="is-complete" name="is_complete" value="0">
                                
                                <div class="mb-6">
                                    <div class="flex justify-between align-center mb-3">
                                        <h3 class="text-lg font-medium text-gray-800 dark:text-gray-200">Détails du formulaire</h3>
                                        <button type="button" class="text-white bg-indigo-600 px-3 py-1 rounded hover:bg-indigo-700" id="edit-btn">
                                            Modifier
                                        </button>
                                    </div>

                                    <div id="user-select-container" class="mb-4 hidden">
                                        <label for="user_id" class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-300">Affecter à un utilisateur :</label>
                                        <select name="user_id" id="user_id" class="w-full rounded border-gray-300 dark:bg-gray-800 dark:text-gray-200">
                                            @foreach ($users as $user)
                                                <option value="{{ $user->id }}">{{ $user->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="bg-white dark:bg-gray-700 overflow-hidden border border-gray-200 dark:border-gray-600 sm:rounded-lg">
                                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-600">
                                            <thead class="bg-gray-50 dark:bg-gray-800">
                                                <tr>
                                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Clé</th>
                                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Valeur</th>
                                                </tr>
                                            </thead>
                                            <tbody class="bg-white dark:bg-gray-700 divide-y divide-gray-200 dark:divide-gray-600">
                                                @foreach($selectedDemande->champs as $champ)
                                                    <tr>
                                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-gray-200">{{ $champ->key }}</td>
                                                        <td class="px-6 py-4 text-sm text-gray-500 dark:text-gray-300">
                                                            <input type="text" name="champs[{{ $champ->id }}]" value="{{ $champ->value }}" placeholder="Valeur de {{ $champ->key }}" class="field-input w-full bg-gray-100 dark:bg-gray-600 text-gray-900 dark:text-gray-200 rounded px-2 py-1" disabled>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>

                                    <div class="mt-4 hidden" id="submit-container">
                                        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">
                                            Affecter
                                        </button>
                                    </div>
                                </div>
                            </form>
                            
                        @else
                            <div class="flex flex-col items-center justify-center h-96 text-center">
                                <svg class="w-16 h-16 text-gray-400 dark:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
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
        function activerEditForm() {
            
            document.querySelectorAll('input[type="text"]').forEach(input => {
                input.disabled = false;
                input.classList.remove('bg-gray-100', 'dark:bg-gray-600');
                input.classList.add('bg-white', 'dark:bg-gray-800', 'border', 'border-gray-300', 'dark:border-gray-600');
            });

            document.getElementById('user-select-container').classList.remove('hidden');
            document.getElementById('submit-container').classList.remove('hidden');
            
            // Cacher le bouton modifier
            const editBtn = document.getElementById('edit-btn');
            if (editBtn) {
                editBtn.style.display = 'none';
            }
        }

        // Fonction pour vérifier si tous les champs ont été remplis
        function checkIfFormIsComplete() {
            const inputs = document.querySelectorAll('.field-input');
            let allFieldsFilled = true;
            
            // Vérifier si tous les champs ont une valeur
            inputs.forEach(input => {
                if (!input.value || input.value.trim() === '') {
                    allFieldsFilled = false;
                }
            });
            
            // Mettre à jour le champ caché
            document.getElementById('is-complete').value = allFieldsFilled ? '1' : '0';
            
            return allFieldsFilled;
        }

        // S'assurer que le DOM est complètement chargé avant d'attacher les événements
        document.addEventListener('DOMContentLoaded', function() {
            // Attacher explicitement l'événement de clic au bouton
            const editBtn = document.getElementById('edit-btn');
            if (editBtn) {
                editBtn.addEventListener('click', activerEditForm);
            }
            
            // Vérifier si le formulaire est complet au chargement
            const isComplete = checkIfFormIsComplete();
            const timerContainer = document.getElementById('timer-container');
            const toastSuccess = document.getElementById('toast-success');
            
            // Si le formulaire est complet, afficher le message et ne pas lancer le minuteur
            if (isComplete) {
                if (timerContainer) {
                    timerContainer.innerHTML = '<span class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">Formulaire complet</span>';
                }
                
                // Afficher la notification de succès
                if (toastSuccess) {
                    toastSuccess.classList.remove('hidden');
                    
                    // Masquer après 5 secondes
                    setTimeout(() => {
                        toastSuccess.classList.add('hidden');
                    }, 5000);
                }
                
                return; // Ne pas lancer le minuteur
            }
            
            // Code pour le minuteur et les notifications toast
            const lastUpdatedStr = JSON.parse(document.getElementById('last-updated-data')?.dataset?.value || 'null');
            
            if (!lastUpdatedStr) {
                if (timerContainer) timerContainer.textContent = '';
                return;
            }

            let timeLeft = 10;
            if (timerContainer) timerContainer.textContent = `Temps restant : ${timeLeft} secondes`;

            const countdown = setInterval(() => {
                timeLeft--;
                if (timerContainer) timerContainer.textContent = `Temps restant : ${timeLeft} secondes`;

                if (timeLeft <= 0) {
                    clearInterval(countdown);
                    if (timerContainer) timerContainer.textContent = 'Temps écoulé.';

                    // Affiche la notification toast
                    const toast = document.getElementById('toast-default');
                    if (toast) toast.classList.remove('hidden');
                }
            }, 1000);
              
            // Gestionnaire pour fermer le toast d'alerte
            const toastCloseBtn = document.getElementById('toast-close-btn');
            if (toastCloseBtn) {
                toastCloseBtn.addEventListener('click', () => {
                    const toast = document.getElementById('toast-default');
                    if (toast) toast.classList.add('hidden');
                });
            }
            
            // Gestionnaire pour fermer le toast de succès
            const toastSuccessCloseBtn = document.getElementById('toast-success-close-btn');
            if (toastSuccessCloseBtn) {
                toastSuccessCloseBtn.addEventListener('click', () => {
                    const toast = document.getElementById('toast-success');
                    if (toast) toast.classList.add('hidden');
                });
            }
            
            // Ajouter des écouteurs pour détecter les changements dans les champs
            document.querySelectorAll('.field-input').forEach(input => {
                input.addEventListener('change', function() {
                    // Vérifier si le formulaire est complet après chaque changement
                    const isNowComplete = checkIfFormIsComplete();
                    
                    if (isNowComplete) {
                        // Arrêter le minuteur et afficher le message
                        clearInterval(countdown);
                        
                        if (timerContainer) {
                            timerContainer.innerHTML = '<span class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">Formulaire complet</span>';
                        }
                        
                        // Cacher la notification d'alerte si elle est visible
                        const toast = document.getElementById('toast-default');
                        if (toast && !toast.classList.contains('hidden')) {
                            toast.classList.add('hidden');
                        }
                        
                        // Afficher la notification de succès
                        if (toastSuccess) {
                            toastSuccess.classList.remove('hidden');
                            
                            // Masquer après 5 secondes
                            setTimeout(() => {
                                toastSuccess.classList.add('hidden');
                            }, 5000);
                        }
                    }
                });
            });
        });
    </script>

    <style>
        @keyframes fadeInOut {
            0%, 100% {opacity: 0;}
            10%, 90% {opacity: 1;}
        }
        .animate-fadeInOut {
            animation: fadeInOut 4s ease forwards;
        }
    </style>
    
</x-app-layout>