<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Création Table Budgétaire') }}
        </h2>
    </x-slot>

    <div class="py-8 mt-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg p-6">

                @if(session('success'))
                    <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 3000)" x-show="show"
                         class="mb-4 px-4 py-2 text-green-800 bg-green-200 border border-green-300 rounded transition-opacity duration-1000 ease-in-out">
                        {{ session('success') }}
                    </div>
                @endif

                <form action="{{ route('budget-tables.store') }}" method="POST">
                    @csrf

                    <div class="mb-4">
                        <label for="title" class="block font-medium dark:text-white">Titre du Budget</label>
                        <input type="text" name="title" id="title" placeholder="Titre du Budget"
                               class="w-full border rounded p-2 dark:bg-gray-800 dark:text-white" required>
                    </div>

                    <div class="mb-4">
                        <label for="prevision" class="block font-medium dark:text-white">Prévisions Budgétaires (année)</label>
                        <input type="number" name="prevision" id="prevision" placeholder="Prévisions Budgétaires (année)"
                               class="w-full border rounded p-2 dark:bg-gray-800 dark:text-white" required>
                    </div>

                    <table class="min-w-full table-auto border mb-4">
                        <thead>
                            <tr class="bg-gray-100">
                                <th class="border px-4 py-2 dark:bg-gray-800 dark:text-white">Imputation comptable</th>
                                <th class="border px-4 py-2 dark:bg-gray-800 dark:text-white">Intitulé</th>
                                <th class="border px-4 py-2 dark:bg-gray-800 dark:text-white">Budget Prévisionnel</th>
                                <th class="border px-4 py-2 dark:bg-gray-800 dark:text-white">Atterrissage</th>
                                <th class="border px-4 py-2 dark:bg-gray-800 dark:text-white text-center">Small Title</th>
                                <th class="border px-4 py-2 dark:bg-gray-800 dark:text-white">Action</th>
                            </tr>
                        </thead>
                        <tbody id="rows">
                            <tr>
                                <td class="border px-4 py-2"><input type="text" name="rows[0][imputation]" class="w-full p-1 border rounded dark:bg-gray-800 dark:text-white"></td>
                                <td class="border px-4 py-2"><input type="text" name="rows[0][intitule]" class="w-full p-1 border rounded dark:bg-gray-800 dark:text-white"></td>
                                <td class="border px-4 py-2"><input type="number" name="rows[0][previsionnel]" class="w-full p-1 border rounded dark:bg-gray-800 dark:text-white"></td>
                                <td class="border px-4 py-2"><input type="number" name="rows[0][atterrissage]" class="w-full p-1 border rounded dark:bg-gray-800 dark:text-white"></td>
                                <td class="border px-4 py-2 text-center">
                                    <input type="hidden" name="rows[0][is_header]" value="0">
                                    <input type="checkbox" name="rows[0][is_header]" value="1" class="w-5 h-5 is-header-checkbox">
                                </td>


                                <td class="border px-4 py-2 text-center">
                                    <i id="curso" class="fas fa-plus cursor-pointer text-green-600 dark:text-green-400 text-xl" onclick="addRow()"></i>
                                </td>
                            </tr>
                        </tbody>
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
        let rowIndex = 1;

        function addRow() {
            const table = document.getElementById('rows');
            const row = document.createElement('tr');

            row.innerHTML = `
                <td class="border px-4 py-2"><input type="text" name="rows[${rowIndex}][imputation]" class="w-full p-1 border rounded dark:bg-gray-800 dark:text-white"></td>
                <td class="border px-4 py-2"><input type="text" name="rows[${rowIndex}][intitule]" class="w-full p-1 border rounded dark:bg-gray-800 dark:text-white"></td>
                <td class="border px-4 py-2"><input type="number" name="rows[${rowIndex}][previsionnel]" class="w-full p-1 border rounded dark:bg-gray-800 dark:text-white"></td>
                <td class="border px-4 py-2"><input type="number" name="rows[${rowIndex}][atterrissage]" class="w-full p-1 border rounded dark:bg-gray-800 dark:text-white"></td>
                <td class="border px-4 py-2 text-center">
                    <input type="hidden" name="rows[${rowIndex}][is_header]" value="0">
                    <input type="checkbox" name="rows[${rowIndex}][is_header]" value="1" class="w-5 h-5 is-header-checkbox">
                </td>

                <td class="border px-4 py-2 text-center">
                    <i class="fas fa-minus cursor-pointer text-red-500 text-xl" onclick="removeRow(this)" title="Remove Row"></i>
                </td>
            `;
            table.appendChild(row);
            rowIndex++;
        }

        function removeRow(button) {
            const row = button.closest('tr');
            row.remove();
        }


        document.addEventListener('change', function (e) {
        if (e.target.classList.contains('is-header-checkbox')) {
            const row = e.target.closest('tr','input');
            if (e.target.checked) {
                row.classList.add('bg-gray-200' ,'dark:bg-gray-700',);
            } else {
                row.classList.remove('bg-gray-200' ,'dark:bg-gray-700',);
            }
        }
    });

    window.addEventListener('DOMContentLoaded', () => {
        document.querySelectorAll('.is-header-checkbox').forEach(cb => {
            const row = cb.closest('tr');
            if (cb.checked) {
                row.classList.add('bg-gray-200', 'font-bold');
            }
        });
    });
    </script>

    <style>
        input[type=number]::-webkit-inner-spin-button,
        input[type=number]::-webkit-outer-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }

        input[type=number] {
            -moz-appearance: textfield;
        }

        #curso {
            cursor: pointer;
        }
    </style>
</x-app-layout>
