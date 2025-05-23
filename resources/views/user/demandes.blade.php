<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Mes Demandes') }}
                <span class="ml-2 text-sm font-normal text-gray-500 dark:text-gray-400">
                    ({{ $mesdemandes->total() }} demande{{ $mesdemandes->total() > 1 ? 's' : '' }})
                </span>
            </h2>
        </div>
    </x-slot>

    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6">

            {{-- Toast nouvelle demande (bleu, top-5) --}}
            <div id="toast-new"
                 class="hidden fixed top-5 right-5 flex items-center w-full max-w-xs p-4 text-blue-700 bg-blue-100 rounded-lg shadow-sm dark:text-blue-400 dark:bg-blue-900"
                 role="alert" aria-live="assertive" aria-atomic="true">
                <div class="inline-flex items-center justify-center shrink-0 w-8 h-8 text-blue-600 bg-blue-200 rounded-lg dark:bg-blue-800 dark:text-blue-200">
                    <svg class="w-4 h-4" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 18 20">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    <span class="sr-only">Info icon</span>
                </div>
                <div id="toast-new-message" class="ms-3 text-sm font-normal"></div>
                <button type="button" id="toast-new-close-btn"
                        class="ms-auto -mx-1.5 -my-1.5 bg-white text-blue-400 hover:text-blue-900 rounded-lg
                               focus:ring-2 focus:ring-blue-300 p-1.5 hover:bg-blue-100 inline-flex items-center justify-center
                               h-8 w-8 dark:text-blue-500 dark:hover:text-white dark:bg-blue-800 dark:hover:bg-blue-700"
                        aria-label="Close">
                    <span class="sr-only">Close</span>
                    <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                    </svg>
                </button>
            </div>

            {{-- Toast demande en retard (rouge, top-24) --}}
            <div id="toast-late"
                 class="hidden fixed top-24 right-5 flex items-center w-full max-w-xs p-4 text-red-700 bg-red-100 rounded-lg shadow-sm dark:text-red-400 dark:bg-red-900"
                 role="alert" aria-live="assertive" aria-atomic="true">
                <div class="inline-flex items-center justify-center shrink-0 w-8 h-8 text-red-600 bg-red-200 rounded-lg dark:bg-red-800 dark:text-red-200">
                    <svg class="w-4 h-4" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 18 20">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.147 15.085a7.159 7.159 0 0 1-6.189 3.307A6.713 6.713 0 0 1 3.1 15.444c-2.679-4.513.287-8.737.888-9.548A4.373 4.373 0 0 0 5 1.608c1.287.953 6.445 3.218 5.537 10.5 1.5-1.122 2.706-3.01 2.853-6.14 1.433 1.049 3.993 5.395 1.757 9.117Z"/>
                    </svg>
                    <span class="sr-only">Warning icon</span>
                </div>
                <div id="toast-late-message" class="ms-3 text-sm font-normal"></div>
                <button type="button" id="toast-late-close-btn"
                        class="ms-auto -mx-1.5 -my-1.5 bg-white text-red-400 hover:text-red-900 rounded-lg
                               focus:ring-2 focus:ring-red-300 p-1.5 hover:bg-red-100 inline-flex items-center justify-center
                               h-8 w-8 dark:text-red-500 dark:hover:text-white dark:bg-red-800 dark:hover:bg-red-700"
                        aria-label="Close">
                    <span class="sr-only">Close</span>
                    <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                    </svg>
                </button>
            </div>

            {{-- Liste des demandes --}}
            @if($mesdemandes->count() > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-700">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">ID</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Titre</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Date de création</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Temps écoulé</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                            @foreach($mesdemandes as $demande)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors duration-150">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-gray-100">
                                        #{{ $demande->id }}
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-900 dark:text-gray-100">
                                        <div class="font-medium">{{ $demande->titre ?? 'N/A' }}</div>
                                        @if(!empty($demande->category))
                                            <div class="text-xs text-gray-500 dark:text-gray-400">{{ $demande->category }}</div>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                        <div>{{ $demande->created_at->format('d/m/Y') }}</div>
                                        <div class="text-xs">{{ $demande->created_at->format('H:i') }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                        {{ $demande->created_at->diffForHumans() }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <div class="flex space-x-3">
                                            <a href="{{ route('demande.show', $demande->id) }}"
                                               class="inline-flex items-center px-3 py-1 bg-blue-600 text-white text-sm rounded-md hover:bg-blue-700 transition-colors duration-150">
                                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                                </svg>
                                                Voir
                                            </a>

                                            <a href="{{ route('demande.remplir', $demande->id) }}"
                                               class="inline-flex items-center px-3 py-1 bg-green-600 text-white text-sm rounded-md hover:bg-green-700 transition-colors duration-150">
                                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                                </svg>
                                                Remplir
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    {{-- Pagination --}}
                    <div class="mt-4">
                        {{ $mesdemandes->links() }}
                    </div>
                </div>
            @else
                <div class="text-center py-12">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-gray-100">Aucune demande</h3>
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Vous n'avez pas encore créé de demandes.</p>
                </div>
            @endif
        </div>
    </div>

    <script>
        function showToast(type, message) {
            if(type === 'new') {
                const toastNew = document.getElementById('toast-new');
                const toastNewMsg = document.getElementById('toast-new-message');
                toastNewMsg.textContent = message;
                toastNew.classList.remove('hidden');
            } else if(type === 'late') {
                const toastLate = document.getElementById('toast-late');
                const toastLateMsg = document.getElementById('toast-late-message');
                toastLateMsg.textContent = message;
                toastLate.classList.remove('hidden');
            }
        }

        document.addEventListener('DOMContentLoaded', () => {
            document.getElementById('toast-new-close-btn').addEventListener('click', () => {
                document.getElementById('toast-new').classList.add('hidden');
            });
            document.getElementById('toast-late-close-btn').addEventListener('click', () => {
                document.getElementById('toast-late').classList.add('hidden');
            });

            @if(isset($nouvellesDemandes) && $nouvellesDemandes->count() > 0)
                showToast('new', "Vous avez {{ $nouvellesDemandes->count() }} nouvelle{{ $nouvellesDemandes->count() > 1 ? 's' : '' }} demande{{ $nouvellesDemandes->count() > 1 ? 's' : '' }} à traiter.");
            @endif

            @if(isset($demandesEnRetard) && $demandesEnRetard->count() > 0)
                showToast('late', "Certaines demandes n'ont pas été remplies depuis plus d'une minute !");
            @endif
        });
    </script>
</x-app-layout>
