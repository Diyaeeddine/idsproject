<title>Mes Factures</title>
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            <i class="fas fa-file-invoice-dollar text-indigo-500 mr-3"></i>
            {{ __('Mes Factures') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="space-y-6">
                
                @if($factures->isEmpty())
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6 text-center">
                        <p class="text-gray-500 dark:text-gray-400">
                            Vous n'avez aucune facture pour le moment.
                        </p>
                    </div>
                @else
                    @foreach($factures as $facture)
                        {{-- FIX: The entire card is now a clickable link --}}
                        <a href="{{ route('factures.show', $facture) }}" class="block hover:bg-gray-50 dark:hover:bg-gray-700/50 transition duration-150 ease-in-out">
                            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                                <div class="p-6 flex flex-col md:flex-row justify-between md:items-center space-y-4 md:space-y-0">
                                    
                                    {{-- Left Side: Invoice Details --}}
                                    <div class="flex-grow">
                                        <div class="flex items-center space-x-4">
                                            <div class="bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200 font-bold px-3 py-1 rounded-md text-sm">
                                                {{ $facture->numero_facture }}
                                            </div>
                                            
                                            <div>
                                                @if($facture->statut === 'payée')
                                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                                                        <i class="fas fa-check-circle mr-1.5"></i>
                                                        Payée
                                                    </span>
                                                @else
                                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200">
                                                        <i class="fas fa-exclamation-circle mr-1.5"></i>
                                                        Non Payée
                                                    </span>
                                                @endif
                                            </div>
                                        </div>

                                        <div class="mt-4 text-sm text-gray-600 dark:text-gray-400 space-y-1">
                                            <p><strong class="font-semibold text-gray-700 dark:text-gray-300">Bateau:</strong> {{ $facture->contrat?->navire?->nom ?? 'N/A' }}</p>
                                            <p><strong class="font-semibold text-gray-700 dark:text-gray-300">Date de facturation:</strong> {{ \Carbon\Carbon::parse($facture->date_facture)->format('d F Y') }}</p>
                                        </div>
                                    </div>

                                    {{-- Right Side: Amount and Download Button --}}
                                    <div class="text-left md:text-right flex-shrink-0">
                                        <p class="text-xl font-semibold text-gray-900 dark:text-gray-100">
                                            {{ number_format($facture->total_ttc, 2, ',', ' ') }} DH
                                        </p>
                                        
                                        {{-- We wrap the button in a div to prevent the click on the card from also "clicking" the button --}}
                                        <div class="relative z-10">
                                            <a href="{{ route('factures.downloadPDF', $facture) }}" class="mt-2 inline-flex items-center px-4 py-2 bg-gray-800 dark:bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-blue-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                                                <i class="fas fa-download mr-2"></i>
                                                Télécharger
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </a>
                    @endforeach
                @endif
            </div>
        </div>
    </div>
</x-app-layout>