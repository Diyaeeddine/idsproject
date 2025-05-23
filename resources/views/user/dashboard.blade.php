<x-app-layout>
    <x-slot name="header">
        <div class='flex justify-between items-center'>

            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Dashboard') }}
            </h2>

        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100 font-bold">
                    {{ __("You're logged in as an User!") }}
                </div>
            </div>
        </div>
    </div>

    {{-- Toast nouvelle demande (bleu, top-5 right-5) --}}
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

    {{-- Toast demande en retard (rouge, top-24 right-5) --}}
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
