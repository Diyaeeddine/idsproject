<!-- resources/views/demande/add-demand.blade.php -->
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

                    <form method="POST" action="{{ route('demande.store-demande') }}">
                        @csrf
                        
                        <input type="text" name="name" id="name" value='{{$user->name}}' required hidden>
                        <input type="text" name="email" id="email" value='{{$user->email}}' required hidden>
                        
                        <!-- Type de demande -->
                        <div>
                            <x-input-label for="typeDemande" :value="__('Type de demande')" />
                            <x-text-input id="typeDemande" class="block mt-1 w-full" type="text" name="typeDemande" :value="old('typeDemande')" required autofocus />
                            <x-input-error :messages="$errors->get('typeDemande')" class="mt-2" />
                        </div>

                        <!-- Description de la demande -->
                        <div class="mt-4">
                            <x-input-label for="descDemande" :value="__('Description de la demande')" />
                            <textarea id="descDemande" 
                                class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm" 
                                name="descDemande" 
                                rows="4" 
                                required>{{ old('descDemande') }}</textarea>
                            <x-input-error :messages="$errors->get('descDemande')" class="mt-2" />
                        </div>

                        <!-- Justification -->
                        <div class="mt-4">
                            <x-input-label for="justDemande" :value="__('Justification')" />
                            <x-text-input id="justDemande" class="block mt-1 w-full" type="text" name="justDemande" :value="old('justDemande')" required />
                            <x-input-error :messages="$errors->get('justDemande')" class="mt-2" />
                        </div>

                        <!-- Durée -->
                        <div class="mt-4">
                            <x-input-label for="duree" :value="__('Durée')" />
                            <select id="duree" name="duree" 
                                class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                                <option value="temporaire">Temporaire</option>
                                <option value="permanente">Permanente</option>
                            </select>
                            <x-input-error :messages="$errors->get('duree')" class="mt-2" />
                        </div>

                        <!-- Urgence -->
                        <div class="mt-4">
                            <x-input-label for="urgence" :value="__('Urgence')" />
                            <select id="urgence" name="urgence" 
                                class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                                <option value="faible">Faible</option>
                                <option value="moyenne">Moyenne</option>
                                <option value="haute">Haute</option>
                            </select>
                            <x-input-error :messages="$errors->get('urgence')" class="mt-2" />
                        </div>

                        <div class="flex items-center justify-end mt-6">
                            <x-primary-button class="ms-3">
                                {{ __('Créer la demande') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>