<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet"> 
<script src="//unpkg.com/alpinejs" defer></script>

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight dark:text-white">
            {{ __('Création Table Budgétaire') }}
        </h2>
    </x-slot>

    <div class="py-8 mt-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg p-6">
                @if(session('success'))
                    <div x-data="{ show: true }"
                         class="mb-4 px-4 py-2 text-green-800 bg-green-200 border border-green-300 rounded transition-opacity duration-1000 ease-in-out">
                        {{ session('success') }}
                    </div>
                @endif

                <form action="{{ route('budget-tables.store') }}" method="POST">
                    @csrf

                    <div class="mb-4">
                        <label for="title" class="block font-medium dark:text-white">Titre du Budget</label>
                        <input type="text" name="title" id="title"
                               class="w-full border rounded p-2 dark:bg-gray-800 dark:text-white" required>
                    </div>

                    <div class="mb-4">
                        <label for="prevision" class="block font-medium dark:text-white">Prévisions Budgétaires (année)</label>
                        <input type="number" name="prevision" id="prevision"
                               class="w-full border rounded p-2 dark:bg-gray-800 dark:text-white" required>
                    </div>

                    <div class="flex justify-between mb-4">
                        <button type="button" onclick="addRow()"
                                class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">
                            <i class="fas fa-plus"></i> Ajouter une ligne
                        </button>
                        <button type="button" onclick="addBlueTitleRow()"
                                class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                            <i class="fas fa-plus"></i> Ajouter un titre bleu
                        </button>
                    </div>

                    <table class="min-w-full table-auto border mb-4">
                        <thead>
                            <tr class="bg-gray-100 dark:bg-gray-900">
                                <th class="border px-4 py-2 dark:text-white">Imputation comptable</th>
                                <th class="border px-4 py-2 dark:text-white">Intitulé</th>
                                <th class="border px-4 py-2 dark:text-white">Budget Prévisionnel</th>
                                <th class="border px-4 py-2 dark:text-white">Atterrissage</th>
                                <th class="border px-4 py-2 text-center dark:text-white">Small Title</th>
                                <th class="border px-4 py-2 text-center dark:text-white">Action</th>
                            </tr>
                        </thead>
                        <tbody id="rows"></tbody>
                    </table>

                    <div class="text-right">
                        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition duration-300">
                            Enregistrer
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        let rowIndex = 0;

        function addRow() {
            const table = document.getElementById('rows');
            const row = document.createElement('tr');
            row.innerHTML = `
                <td class="border px-4 py-2">
                    <input type="text" name="rows[${rowIndex}][imputation]" class="w-full p-1 border rounded dark:bg-gray-800 dark:text-white">
                </td>
                <td class="border px-4 py-2">
                    <input type="text" name="rows[${rowIndex}][intitule]" class="w-full p-1 border rounded dark:bg-gray-800 dark:text-white">
                </td>
                <td class="border px-4 py-2">
                    <input type="number" name="rows[${rowIndex}][previsionnel]" class="w-full p-1 border rounded dark:bg-gray-800 dark:text-white">
                </td>
                <td class="border px-4 py-2">
                    <input type="number" name="rows[${rowIndex}][atterrissage]" class="w-full p-1 border rounded dark:bg-gray-800 dark:text-white">
                </td>
                <td class="border px-4 py-2 text-center">
                    <input type="hidden" name="rows[${rowIndex}][is_header]" value="0">
                    <input type="checkbox" name="rows[${rowIndex}][is_header]" value="1" class="w-5 h-5 is-header-checkbox">
                </td>
                <td class="border px-4 py-2 text-center">
                    <i class="fas fa-minus cursor-pointer text-red-500 text-xl" onclick="removeRow(this)" title="Supprimer"></i>
                </td>
            `;
            table.appendChild(row);
            rowIndex++;
        }

        function addBlueTitleRow() {
        const table = document.getElementById('rows');
        const row = document.createElement('tr');
        row.innerHTML = `
            <td class="border px-4 py-2 bg-blue-100 dark:bg-blue-900" colspan="4">
                <input type="hidden" name="rows[${rowIndex}][imputation]" value="">
                <input type="hidden" name="rows[${rowIndex}][intitule]" value="">
                <input type="hidden" name="rows[${rowIndex}][previsionnel]" value="">
                <input type="hidden" name="rows[${rowIndex}][atterrissage]" value="">
                <input type="text" name="rows[${rowIndex}][b_title]" placeholder="Titre bleu"
                       class="w-full p-2 border border-blue-300 rounded dark:bg-blue-900 dark:text-white font-semibold">
            </td>
            <td class="border px-4 py-2 bg-blue-100 dark:bg-blue-900 text-center">
                <!-- Blue titles don't use is_header -->
                <input type="hidden" name="rows[${rowIndex}][is_header]" value="0">
            </td>
            <td class="border px-4 py-2 bg-blue-100 dark:bg-blue-900 text-center">
                <i class="fas fa-minus cursor-pointer text-red-500 text-xl" onclick="removeRow(this)" title="Supprimer"></i>
            </td>
        `;
        table.appendChild(row);
        rowIndex++;
    }


        function removeRow(button) {
            button.closest('tr').remove();
        }

        document.addEventListener('change', function (e) {
            if (e.target.classList.contains('is-header-checkbox')) {
                const row = e.target.closest('tr');
                row.classList.toggle('bg-gray-200', e.target.checked);
                row.classList.toggle('dark:bg-gray-700', e.target.checked);
                row.classList.toggle('font-semibold', e.target.checked);
            }
        });
    </script>
</x-app-layout>