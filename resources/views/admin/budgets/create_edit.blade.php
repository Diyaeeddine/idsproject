@php
    $isEdit = $budget->exists;
@endphp

<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ $isEdit ? 'Modifier Budget' : 'Ajouter Budget' }}
            </h2>
            <a href="{{ route('budgets.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-100 dark:bg-gray-700 border rounded-md font-semibold text-xs text-gray-700 dark:text-gray-300 uppercase tracking-widest shadow-sm hover:bg-gray-200 dark:hover:bg-gray-600">
                Retour
            </a>
        </div>
    </x-slot>

    <div class="py-12 max-w-xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg p-6">
            <form method="POST" action="{{ $isEdit ? route('budgets.update', $budget->id) : route('budgets.store') }}">
                @csrf
                @if($isEdit)
                    @method('PUT')
                @endif

                <div class="mb-4">
                    <label for="intitule" class="block mb-1 font-medium text-gray-700 dark:text-gray-300">Intitulé</label>
                    <input type="text" name="intitule" id="intitule" value="{{ old('intitule', $budget->intitule) }}" required
                        class="w-full rounded border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white px-3 py-2">
                    @error('intitule')
                        <p class="text-red-600 mt-1 text-sm">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="budget_previsionnel" class="block mb-1 font-medium text-gray-700 dark:text-gray-300">Budget Prévisionnel</label>
                    <input type="number" step="0.01" min="0" name="budget_previsionnel" id="budget_previsionnel" value="{{ old('budget_previsionnel', $budget->budget_previsionnel) }}" required
                        class="w-full rounded border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white px-3 py-2">
                    @error('budget_previsionnel')
                        <p class="text-red-600 mt-1 text-sm">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="atterrissage" class="block mb-1 font-medium text-gray-700 dark:text-gray-300">Date d'atterrissage</label>
<input type="date" name="atterrissage" id="atterrissage"
    value="{{ old('atterrissage', $budget->atterrissage ? \Carbon\Carbon::parse($budget->atterrissage)->format('Y-m-d') : '') }}"
    required
    class="w-full rounded border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white px-3 py-2">

                    @error('atterrissage')
                        <p class="text-red-600 mt-1 text-sm">{{ $message }}</p>
                    @enderror
                </div>

                <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">
                    {{ $isEdit ? 'Mettre à jour' : 'Ajouter' }}
                </button>
            </form>
        </div>
    </div>
</x-app-layout>
