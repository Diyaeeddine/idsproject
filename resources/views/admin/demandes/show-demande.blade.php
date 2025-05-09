<x-app-layout>
  <x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
      Détails de la demande
    </h2>
  </x-slot>

  <div class="py-6">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
      <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
        <div class="flex flex-col md:flex-row">

          {{-- Sidebar avec navigation --}}
          <aside class="w-full md:w-1/4 bg-gray-50 dark:bg-gray-900 border-r p-4 md:h-screen md:overflow-auto">
            <h2 class="text-lg font-semibold mb-4 text-gray-800 dark:text-gray-200">
              Formulaires
            </h2>
            <nav class="space-y-1">
              @php
                // Récupération rapide des demandes si la variable n'existe pas
                $demandesList = $demandes ?? \App\Models\Demande::all();

                // Récupération de la demande sélectionnée si elle n'existe pas
                if (!isset($selectedDemande)) {
                    $id = request()->route('id') ?? ($demandesList->first()->id ?? null);
                    $selectedDemande = $id ? \App\Models\Demande::find($id) : null;
                }
              @endphp

              @foreach($demandesList as $d)
                <a href="{{ route('demande', $d->id) }}"
                   class="block px-3 py-2 rounded-md text-sm
                     {{ $selectedDemande && $selectedDemande->id === $d->id
                        ? 'bg-indigo-50 text-indigo-700 dark:bg-indigo-900 dark:text-indigo-200'
                        : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800' }}">
                  Demande {{ $d->id }}
                </a>
              @endforeach
            </nav>
          </aside>

          {{-- Content principal --}}
          <main class="w-full md:w-3/4 p-6">
            @if($selectedDemande)
              <div class="flex items-center justify-between mb-6">
                <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-200">
                  Demande {{ $selectedDemande->id }}
                </h2>
              </div>

              {{-- Bloc d'infos simplifié --}}
              <div class="bg-gray-50 dark:bg-gray-900 p-4 rounded-lg mb-6">
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 text-sm text-gray-600 dark:text-gray-400">
                  <div>
                    <span class="font-medium">ID :</span> {{ $selectedDemande->id ?? 'N/A' }}
                  </div>
                  <div>
                    <span class="font-medium">Titre :</span> {{ $selectedDemande->titre ?? 'N/A' }}
                  </div>
                  <div>
                    <span class="font-medium">ID Utilisateur :</span> {{ $selectedDemande->user_i



                        ?? 'N/A' }}
                  </div>
                </div>
              </div>

              {{-- Tableau des détails simplifié --}}
              <div class="bg-white dark:bg-gray-700 p-4 shadow-sm rounded-lg border border-gray-200 dark:border-gray-600">
                <h3 class="text-lg font-medium mb-4 text-gray-800 dark:text-gray-200">
                  Détails de la demande
                </h3>
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-600">
                  <thead class="bg-gray-50 dark:bg-gray-800">
                    <tr>
                      <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">ID</th>
                      <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Titre</th>
                      <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">résponsable</th>
                    </tr>
                  </thead>
                  <tbody class="bg-white dark:bg-gray-700 divide-y divide-gray-200 dark:divide-gray-600">
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-800">
                      <td class="px-4 py-2 text-sm text-gray-900 dark:text-gray-200">{{ $selectedDemande->id }}</td>
                      <td class="px-4 py-2 text-sm text-gray-900 dark:text-gray-200">{{ $selectedDemande->titre }}</td>
<td class="px-4 py-2 text-sm text-gray-900 dark:text-gray-200">
    @if($selectedDemande->user)
        {{ $selectedDemande->user->name }} {{ $selectedDemande->user->prenom ?? '' }} <!-- Ajoutez prénom si nécessaire -->
    @else
        N/A
    @endif
</td>                    </tr>
                  </tbody>
                </table>
              </div>
            @else
              <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4">
                <div class="flex">
                  <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-yellow-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                      <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                    </svg>
                  </div>
                  <div class="ml-3">
                    <p class="text-sm text-yellow-700">
                      Aucune demande sélectionnée ou la demande n'existe pas.
                    </p>
                  </div>
                </div>
              </div>
            @endif
          </main>

        </div>
      </div>
    </div>
  </div>
</x-app-layout>
