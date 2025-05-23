@php
    $user = auth()->user();
@endphp

<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">{{ __('Budgets') }}</h2>
            <a href="{{ route('dashboard') }}" class="inline-flex items-center px-4 py-2 bg-gray-100 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md font-semibold text-xs text-gray-700 dark:text-gray-300 uppercase tracking-widest shadow-sm hover:bg-gray-200 dark:hover:bg-gray-600">
                {{ __('Dashboard') }}
            </a>
        </div>
    </x-slot>

    <div class="py-12 max-w-6xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg p-6">
            <div class="flex justify-between mb-4">
                <h1 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Prévisions Budgétaires "2025"</h1>
<a href="{{ route('budgets.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition">
    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
    </svg>
    Ajouter une ligne
</a>

            </div>

            @if(session('success'))
                <div class="mb-4 p-4 bg-green-100 text-green-800 rounded">{{ session('success') }}</div>
            @endif

            <div class="overflow-x-auto">
                <table class="min-w-full border border-gray-300 dark:border-gray-600 text-sm text-left text-gray-700 dark:text-gray-300">
                    <thead>
                        <tr class="bg-blue-500 text-white text-center">
                            <th class="border border-gray-300 px-3 py-2 font-bold" style="width: 150px;">ID</th>
                            <th class="border border-gray-300 px-3 py-2 font-normal">Intitulé</th>
                            <th class="border border-gray-300 px-3 py-2 font-bold" style="width: 160px;">Budget Prévisionnel</th>
                            <th class="border border-gray-300 px-3 py-2 font-bold" style="width: 160px;">Atterrissage</th>
                            <th class="border border-gray-300 px-3 py-2 font-bold" style="width: 110px;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($budgets as $budget)
                        <tr
                            class="hover:bg-gray-300 dark:hover:bg-gray-700 cursor-pointer"
                            onclick="window.location='{{ route('budgets.show', $budget->id) }}'"
                        >
                            <td class="border border-gray-300 px-3 py-1 font-bold">{{ $budget->id }}</td>
                            <td class="border border-gray-300 px-3 py-1 font-normal">{{ $budget->intitule }}</td>
                            <td class="border border-gray-300 px-3 py-1 font-normal">{{ number_format($budget->budget_previsionnel, 2, ',', ' ') }}</td>
                            <td class="border border-gray-300 px-3 py-1 font-normal">{{ \Carbon\Carbon::parse($budget->atterrissage)->format('d/m/Y') }}</td>
                            <td class="border border-gray-300 px-3 py-1 flex gap-2 justify-center" onclick="event.stopPropagation();">
                                <a href="{{ route('budgets.edit', $budget->id) }}" title="Modifier" class="text-blue-600 hover:text-blue-900" onclick="event.stopPropagation();">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M11 5h6m-6 4h6m-6 4h6m-6 4h6M6 7v10a2 2 0 002 2h8" />
                                    </svg>
                                </a>
                                <form method="POST" action="{{ route('budgets.destroy', $budget->id) }}" onsubmit="return confirm('Confirmer la suppression ?');" class="inline" onclick="event.stopPropagation();">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" title="Supprimer" class="text-red-600 hover:text-red-900">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                        </svg>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center py-4 font-normal text-gray-600 dark:text-gray-400">
                                Aucun budget trouvé.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>

            </div>
        </div>
    </div>
</x-app-layout>
