<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Détails du Budget #{{ $budget->id }}
        </h2>
    </x-slot>

    <div class="py-12 max-w-3xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg p-6 space-y-4">
            <p><strong>Intitulé :</strong> {{ $budget->intitule }}</p>
            <p><strong>Budget Prévisionnel :</strong> {{ number_format($budget->budget_previsionnel, 2, ',', ' ') }} </p>
            <p><strong>Atterrissage :</strong> {{ \Carbon\Carbon::parse($budget->atterrissage)->format('d/m/Y') }}</p>
            <p><strong>Créé le :</strong> {{ $budget->created_at ? $budget->created_at->format('d/m/Y H:i') : 'Non renseigné' }}</p>
            <p><strong>Mis à jour le :</strong> {{ $budget->updated_at ? $budget->updated_at->format('d/m/Y H:i') : 'Non renseigné' }}</p>

            <div class="mt-6">
                <a href="{{ route('budgets.index') }}" class="text-blue-600 hover:underline">
                    ← Retour à la liste des budgets
                </a>
            </div>
        </div>
    </div>
</x-app-layout>
