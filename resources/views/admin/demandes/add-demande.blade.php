@php
    $user=auth()->user();
@endphp

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Demandes') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h1 class="text-lg font-medium mb-6 pb-2 border-b border-gray-200 dark:border-gray-700">{{ __('Créer une demande') }}</h1>

                    @if (session('success'))
                        <div class="bg-green-50 dark:bg-green-900/50 text-green-800 dark:text-green-300 p-4 mb-6 rounded-md">
                            {{ session('success') }}
                        </div>
                    @endif

                    <form action="{{ route('demande.store-demande') }}" method="POST" class="space-y-4">
                        @csrf

                        <div id="custom-fields" class="space-y-2">
                            <div class="flex items-center gap-2">
                                <label for="titre" class="block font-medium text-sm text-gray-700 dark:text-gray-300">Titre: </label>
                                <input type="text" name="titre" id="titre" placeholder="Titre de la demande" class="mt-1 block w-full px-3 py-2 border rounded-md dark:bg-gray-800 dark:text-white" required>                                <button type="button" class="remove-row text-red-500 hover:text-red-700">✕</button>
                            </div>
                        </div>

                        <button type="button" id="add-field" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Ajouter un champ</button>

                        <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">Envoyer</button>
                    </form>

<script>
    let index = 1;
    document.getElementById('add-field').addEventListener('click', function () {
        const container = document.getElementById('custom-fields');
        const div = document.createElement('div');
        div.className = "flex items-center gap-2";
        div.innerHTML = `
            <input type="text" name="fields[${index}][key]" placeholder="Nom du champ" class="w-1/2 px-3 py-2 border rounded-md dark:bg-gray-800 dark:text-white" required>
            <input type="text" name="fields[${index}][value]" placeholder="Valeur" class="w-1/2 px-3 py-2 border rounded-md dark:bg-gray-800 dark:text-white" required>
            <button type="button" class="remove-row text-red-500 hover:text-red-700">✕</button>
        `;
        container.appendChild(div);
        index++;
    });

    document.addEventListener('click', function(e) {
        if (e.target && e.target.classList.contains('remove-row')) {
            e.target.parentElement.remove();
        }
    });
</script>


                </div>
            </div>
        </div>
    </div>
</x-app-layout>
