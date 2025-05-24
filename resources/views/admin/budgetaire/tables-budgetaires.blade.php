<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800 dark:text-white">Tables Budgétaires</h2>
    </x-slot>

   <div class="flex flex-col md:flex-row h-full">
    <div class="w-full md:w-1/4 bg-white text-gray-800 dark:bg-gray-800 border-b md:border-b-0 md:border-r border-gray-300 dark:border-gray-700 p-4">
        <h3 class="text-lg font-bold mb-4 text-gray-800 dark:text-white">Titres</h3>
        <ul class="space-y-2">
            @foreach ($tables as $table)
                <li>
                    <a href="{{ route('budget-tables.show', $table->id) }}"
                       class="block px-3 py-2 rounded text-sm {{ request()->is('tables-budgetaires/' . $table->id) ? 'bg-blue-600 text-white' : 'dark:bg-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 dark:text-white' }}">
                        {{ $table->title }}
                    </a>
                </li>
            @endforeach
        </ul>
    </div>

    <div class="w-full md:w-3/4 p-4 overflow-x-auto">
        @if ($selectedTable)                   

            <div class="mb-4 text-right">
                <a href="{{ route('budget-tables.export', $selectedTable->id) }}" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700 transition duration-200">
                    <i class="fas fa-file-pdf"></i> Exporter en PDF
                </a>
            </div>

            <div class="overflow-x-auto">
                <table class="w-3/4 table-auto border border-black border-collapse text-sm">
                    <thead>
                        <tr class="border border-black">
                            <th colspan="4"
                                class="text-center text-white bg-blue-800 dark:bg-blue-900 dark:text-white text-base md:text-xl font-bold py-2 border border-black">
                                {{ $selectedTable->title }}
                            </th>
                        </tr>

                        <tr class="border border-black">
                            <th colspan="4"
                                class="text-center bg-blue-100 text-base md:text-lg font-semibold py-2 text-black dark:bg-gray-300 dark:text-black border border-black">
                                {{ $selectedTable->prevision_label }}
                            </th>
                        </tr>

                        <tr class="bg-blue-800 text-white text-s md:text-sm dark:bg-blue-900 dark:text-white border border-black">
                            <th class="border border-black px-2 md:px-4 py-2">Imputation<br>comptable</th>
                            <th class="border border-black px-2 md:px-4 py-2">Intitulé</th>
                            <th class="border border-black px-2 md:px-4 py-2">Budget<br>Prévisionnel</th>
                            <th class="border border-black px-2 md:px-4 py-2">Atterrissage</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($selectedTable->entries->sortBy('position') as $entry)
                            @if ($entry->b_title)
                                <tr class="bg-blue-600 text-white dark:bg-blue-900 text-black dark:text-white font-bold">
                                    <td colspan="4" class="border border-black px-2 md:px-4 py-2 text-left">
                                        {{ $entry->b_title }}
                                    </td>
                                </tr>
                            @else
                                <tr class="border border-black {{ $entry->is_header 
                                    ? 'bg-gray-200 text-black font-bold dark:bg-gray-200' 
                                    : 'bg-white text-black dark:bg-white dark:text-black' }}">
                                    <td class="border border-black px-2 md:px-4 py-2">{{ $entry->imputation_comptable }}</td>
                                    <td class="border border-black px-2 md:px-4 py-2">{{ $entry->intitule }}</td>
                                    <td class="border border-black px-2 md:px-4 py-2">{{ $entry->budget_previsionnel }}</td>
                                    <td class="border border-black px-2 md:px-4 py-2">{{ $entry->atterrissage }}</td>
                                </tr>
                            @endif
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <p class="text-gray-600 dark:text-gray-400">Aucune table budgétaire sélectionnée.</p>
        @endif
    </div>
</div>

</x-app-layout>
