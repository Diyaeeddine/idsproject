<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800 dark:text-white">Tables Budgétaires</h2>
    </x-slot>

    <div class="flex h-full">
        <!-- Sidebar -->
        <div class="w-1/4 bg-white text-gray-800 dark:bg-gray-800 border-r border-gray-300 dark:border-gray-700 p-4">
            <h3 class="text-lg font-bold mb-4 text-gray-800 dark:text-white">Titres</h3>
            <ul class="space-y-2">
                @foreach ($tables as $table)
                    <li>
                        <a href="{{ route('budget-tables.show', $table->id) }}"
                           class="block px-3 py-2 rounded {{ request()->is('tables-budgetaires/' . $table->id) ? 'bg-blue-600 text-white' : 'dark:bg-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 dark:text-white' }}">
                            {{ $table->title }}
                        </a>
                    </li>
                @endforeach
            </ul>
        </div>

        <!-- Main content -->
        <div class="w-3/4 p-6">
    @if ($selectedTable)
        <table class="min-w-full table-auto border border-black">
            <thead>
                <!-- Title Row -->
            <th colspan="4" class="text-center text-white bg-blue-800 dark:bg-blue-900 text-xl font-bold py-2">
                {{ $selectedTable->title }}
            </th>

            <!-- Subheading Row -->
            <tr>
                <th colspan="4"
                    class="text-center bg-blue-100 text-lg font-semibold py-2 text-black dark:bg-gray-300 dark:text-black">
                    {{ $selectedTable->prevision_label }}
                </th>
            </tr>

            <!-- Column Headers -->
            <tr class="bg-blue-800 text-white text-sm dark:bg-blue-900">
                    <th class="border border-black dark:border-white px-4 py-2">Imputation<br>comptable</th>
                    <th class="border border-black dark:border-white px-4 py-2">Intitulé</th>
                    <th class="border border-black dark:border-white px-4 py-2">Budget<br>Prévisionnel </th>
                    <th class="border border-black dark:border-white px-4 py-2">Atterrissage</th>
                </tr>
            </thead>

            <tbody>
            @foreach ($selectedTable->entries as $entry)
                <tr class="{{ $entry->is_header 
                    ? 'bg-gray-200 text-black font-bold dark:bg-gray-200' 
                    : 'bg-white text-black dark:bg-white dark:text-black' }}">
                    <td class="border border-black px-4 py-2">{{ $entry->imputation_comptable }}</td>
                    <td class="border border-black px-4 py-2">{{ $entry->intitule }}</td>
                    <td class="border border-black px-4 py-2">{{ $entry->budget_previsionnel }}</td>
                    <td class="border border-black px-4 py-2">{{ $entry->atterrissage }}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
    @else
        <p class="text-gray-600 dark:text-gray-400">Aucune table budgétaire sélectionnée.</p>
    @endif
</div>

    </div>
</x-app-layout>
