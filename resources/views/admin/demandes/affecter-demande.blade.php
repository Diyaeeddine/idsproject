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
                        <div class="space-y-1">
                            @foreach($demandes as $demande)
                                <a href="{{ route('demandes.affecter', $demande->id) }}"
                                   class="block px-3 py-2 rounded-md text-sm {{ request()->route('demande') == $demande->id ? 'bg-indigo-50 text-indigo-700 dark:bg-indigo-900 dark:text-indigo-200' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800' }}">
                                    <div class="font-medium truncate">{{ $demande->titre }}</div>
                                    <div class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                        Créé le {{ \Carbon\Carbon::parse($demande->created_at)->format('d/m/Y') }}
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    </div>

                    {{-- Content --}}
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
                                        ($selectedDemande->statut === 'affecte' ? 'Affecté' : 'Traité')
                                    }}
                                </span>
                            </div>
                                        {{-- Affichage des champs déjà affectés --}}
                                <h3 class="text-lg font-medium text-gray-800 dark:text-gray-200">Champs affectés :</h3>
                                    <div class="bg-white dark:bg-gray-700 overflow-hidden border border-gray-200 dark:border-gray-600 sm:rounded-lg mb-6">
                                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-600">
                                            <thead class="bg-gray-50 dark:bg-gray-800">
                                                <tr>
                                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Clé</th>
                                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Valeur</th>
                                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">responsable</th>
                                                </tr>
                                            </thead>
                                            <tbody class="bg-white dark:bg-gray-700 divide-y divide-gray-200 dark:divide-gray-600">
                                                @foreach($selectedDemande->champs as $champ)
                                                    @if($champ->user_id)  {{-- Seulement les champs affectés --}}
                                                        <tr>
                                                            <td class="px-6 py-4 text-sm text-gray-500 dark:text-gray-300">{{ $champ->key }}</td>
                                                            <td class="px-6 py-4 text-sm text-gray-500 dark:text-gray-300">{{ $champ->value }}</td>
                                                        </tr>
                                                    @endif
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>

                            <form method="POST" action="{{ route('demandes.affecterUsers', $selectedDemande->id) }}">
                                @csrf
                                <div class="mb-6">
                                    <div class="flex justify-between align-center mb-3">
                                        <h3 class="text-lg font-medium text-gray-800 dark:text-gray-200">Détails du formulaire</h3>
                                        <button type="button" class="text-white bg-indigo-600 px-3 py-1 rounded hover:bg-indigo-700" onclick="activerEditForm()">Edit</button>
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
                                                            <input type="text" name="champs[{{ $champ->id }}]" value="{{ $champ->value }}" placeholder="Valeur de {{ $champ->key }}" class="w-full bg-gray-100 dark:bg-gray-600 text-gray-900 dark:text-gray-200 rounded px-2 py-1" disabled>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>

                                    <div class="mt-4 hidden" id="submit-container">
                                        <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded">Suivant</button>
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
        document.querySelectorAll('input[type="text"]').forEach(input => input.disabled = false);
        document.getElementById('user-select-container').classList.remove('hidden');
        document.getElementById('submit-container').classList.remove('hidden');
    }
</script>

</x-app-layout>
