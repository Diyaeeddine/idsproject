<title>Mes Contrats</title>
<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center ">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                <i class="fas fa-file-contract text-gray-500 mr-2"></i>
                {{ __('Mes Contrats') }}
            </h2>
            <a href="{{ route('contrats.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold text-sm rounded-lg shadow-sm transition duration-200">
                <i class="fas fa-plus mr-2"></i>
                Nouveau Contrat
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="space-y-6">

                {{-- This loop checks if there are any contracts. If not, it shows the "empty" message. --}}
                @forelse($contrats as $contrat)
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <div class="flex flex-col sm:flex-row justify-between">
                                <div>
                                    <div class="flex items-center space-x-3">
                                        <h3 class="text-lg font-bold text-gray-900 dark:text-gray-100">
                                            Contrat #{{ $contrat->id }}
                                        </h3>
                                        {{-- Badge for contract type --}}
                                        @if($contrat->type === 'accostage')
                                            <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                Accostage
                                            </span>
                                        @else
                                            <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-200 text-indigo-800">
                                                Randonnée
                                            </span>
                                        @endif
                                    </div>
                                    <div class="mt-4 text-sm text-gray-600 dark:text-gray-400 space-y-2">
                                        <p>
                                            <i class="fas fa-ship fa-fw mr-2 text-gray-400"></i>
                                            <strong>Bateau :</strong> {{ $contrat->navire?->nom ?? 'N/A' }}
                                        </p>
                                        <p>
                                            <i class="fas fa-user fa-fw mr-2 text-gray-400"></i>
                                            <strong>Demandeur :</strong> {{ $contrat->demandeur?->nom ?? 'N/A' }}
                                        </p>
                                        <p>
                                            <i class="fas fa-calendar-alt fa-fw mr-2 text-gray-400"></i>
                                            <strong>Période :</strong> 
                                            {{ \Carbon\Carbon::parse($contrat->date_debut)->format('d/m/Y') }} au {{ \Carbon\Carbon::parse($contrat->date_fin)->format('d/m/Y') }}
                                        </p>
                                    </div>
                                </div>
                                
                                <div class="mt-4 sm:mt-0 flex sm:flex-col sm:items-end sm:justify-center space-x-2 sm:space-x-0 sm:space-y-3">
                                    <a href="{{ route('factures.create', $contrat) }}" class="inline-flex items-center justify-center  bg-green-500 hover:bg-green-600 text-white font-semibold text-xs rounded-md transition" title="Créer une facture">
                                        
                                        <span class=" p-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold text-sm rounded-lg shadow-sm transition duration-200"><i class="fas fa-file-invoice mr-2"></i>Créer Facture</span>
                                    </a>
                                    <a href="{{ route('contrats.downloadPDF', $contrat) }}" class="inline-flex items-center justify-center  bg-gray-700 hover:bg-gray-800 text-white font-semibold text-xs rounded-md transition" title="Télécharger le contrat">
                                        
                                        <span class="p-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold text-sm rounded-lg shadow-sm transition duration-200"><i class="fas fa-download mr-2"></i>Télécharger</span>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-10 text-center text-gray-500 dark:text-gray-400">
                            <i class="fas fa-folder-open fa-3x mb-4"></i>
                            <h3 class="text-lg font-medium">Vous n'avez aucun contrat.</h3>
                            <p class="mt-1">Commencez par en créer un nouveau.</p>
                        </div>
                    </div>
                @endforelse

            </div>
        </div>
    </div>
</x-app-layout>