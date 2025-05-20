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
                                </div>
                            </div>
{{--  <div id="timer-container" class="relative bg-gradient-to-r from-indigo-50 to-indigo-100 dark:from-indigo-900 dark:to-indigo-800 rounded-xl p-5 mb-6 shadow-lg max-w-sm mx-auto ring-1 ring-indigo-300 dark:ring-indigo-700">
  <div class="flex justify-between items-center mb-3">
    <span class="text-sm font-semibold text-indigo-700 dark:text-indigo-300 tracking-wide uppercase select-none">Temps restant</span>
    <span id="timer-text" class="text-lg font-mono font-semibold text-indigo-900 dark:text-indigo-200 select-none">-- s</span>
  </div>
  <div class="w-full h-3 bg-indigo-200 dark:bg-indigo-700 rounded-full overflow-hidden shadow-inner">
    <div id="timer-bar" class="h-3 bg-indigo-600 dark:bg-indigo-400 rounded-full transition-all duration-1000 ease-out"></div>
  </div>
</div>  --}}





                            {{-- Notification Toast (invisible par défaut) --}}
                            {{-- Notification Toast (invisible par défaut) --}}
                            <div id="toast-default" class="hidden fixed bottom-5 right-5 flex items-center w-full max-w-xs p-4 text-red-700 bg-red-100 rounded-lg shadow-sm dark:text-red-400 dark:bg-red-900" role="alert" aria-live="assertive" aria-atomic="true">
                                <div class="inline-flex items-center justify-center shrink-0 w-8 h-8 text-red-600 bg-red-200 rounded-lg dark:bg-red-800 dark:text-red-200">
                                    <svg class="w-4 h-4" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 18 20">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.147 15.085a7.159 7.159 0 0 1-6.189 3.307A6.713 6.713 0 0 1 3.1 15.444c-2.679-4.513.287-8.737.888-9.548A4.373 4.373 0 0 0 5 1.608c1.287.953 6.445 3.218 5.537 10.5 1.5-1.122 2.706-3.01 2.853-6.14 1.433 1.049 3.993 5.395 1.757 9.117Z"/>
                                    </svg>
                                    <span class="sr-only">Fire icon</span>
                                </div>
                                <div class="ms-3 text-sm font-normal">⚠️ Attention : 10 secondes écoulées depuis la dernière affectation !</div>
                                <button type="button" id="toast-close-btn" class="ms-auto -mx-1.5 -my-1.5 bg-white text-red-400 hover:text-red-900 rounded-lg focus:ring-2 focus:ring-red-300 p-1.5 hover:bg-red-100 inline-flex items-center justify-center h-8 w-8 dark:text-red-500 dark:hover:text-white dark:bg-red-800 dark:hover:bg-red-700" aria-label="Close">
                                    <span class="sr-only">Close</span>
                                    <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                                    </svg>
                                </button>
                            </div>

                            <form method="POST" action="{{ route('demande.affecterChamps', $selectedDemande->id) }}">
                                @csrf
                                <div class="mb-6">
                                    <div class="flex justify-between align-center mb-3">
                                        <h3 class="text-lg font-medium text-gray-800 dark:text-gray-200">Détails du formulaire</h3>
                                        <button type="button" id="edit-btn" class="text-white bg-indigo-600 px-3 py-1 rounded hover:bg-indigo-700" onclick="activerEditForm()">Edit</button>
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
                                        <button type="submit" id="next-btn" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded">suivant</button>
                                    </div>
                                </div>
                            </form>
                        @else
                            <div class="flex flex-col items-center justify-center h-96 text-center">
                                <svg class="w-16 h-16 text-gray-400 dark:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                                <p class="mt-2 text-lg font-medium text-gray-600 dark:text-gray-400">Sélectionnez un formulaire</p>
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
        const editBtn = document.getElementById('edit-btn');
        if (editBtn) editBtn.style.display = 'none';
    }

    document.addEventListener('DOMContentLoaded', () => {
        const lastUpdatedStr = @json(session('lastUpdatedAt', null));
        const timerContainer = document.getElementById('timer-container');
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

                // Affiche la notification toast au lieu de alert()
                const toast = document.getElementById('toast-default');
                if (toast) toast.classList.remove('hidden');

                // Pas de fermeture automatique, notification visible jusqu'à clic sur la croix
            }
        }, 1000);

        // Gestion fermeture manuelle du toast
        const toastCloseBtn = document.getElementById('toast-close-btn');
        if (toastCloseBtn) {
            toastCloseBtn.addEventListener('click', () => {
                const toast = document.getElementById('toast-default');
                if (toast) toast.classList.add('hidden');
            });
        }
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
