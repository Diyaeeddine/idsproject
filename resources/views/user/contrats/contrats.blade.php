<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Gestion des Contrats - Bouregreg Marina') }}
        </h2>
        <a href="{{ route('contrats.create') }}"
        class="inline-flex items-center px-6 py-3 bg-primary-600 hover:bg-primary-700 text-white font-semibold rounded-lg shadow-md transition duration-200">
        <i class="fas fa-plus mr-2"></i>
        Créer un contrat
     </a>
    </div>
    </x-slot>

    <div class="py-12">
        <div class="">         
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 ">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-lg font-semibold text-gray-800">
                                <span id="contract-list-title">Liste des Contrats Commerciaux</span>
                            </h3>
                            <div class="flex space-x-2">
                                <input 
                                    type="text" 
                                    placeholder="Rechercher un contrat..." 
                                    class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                >
                                <button class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition-colors">
                                    <i class="fas fa-plus mr-1"></i>
                                    Nouveau
                                </button>
                            </div>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            N° Contrat
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Client
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Navire
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Date
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Statut
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Actions
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200" id="contracts-table-body">
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                            COM-2024-001
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            Ahmed Benali
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            Marina Express
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            15/11/2024
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                Actif
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                                            <button class="text-blue-600 hover:text-blue-900">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            <button class="text-yellow-600 hover:text-yellow-900">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button class="text-red-600 hover:text-red-900">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                            COM-2024-002
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            Fatima Zahra
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            Ocean Blue
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            20/11/2024
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                                En attente
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                                            <button class="text-blue-600 hover:text-blue-900">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            <button class="text-yellow-600 hover:text-yellow-900">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button class="text-red-600 hover:text-red-900">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
        </div>
    </div>

    <script>
        function showContractType(type) {
            document.querySelectorAll('.tab-button').forEach(tab => {
                tab.classList.remove('bg-blue-600', 'text-white');
                tab.classList.add('bg-gray-200', 'text-gray-700');
            });

            const activeTab = document.getElementById(`tab-${type}`);
            activeTab.classList.remove('bg-gray-200', 'text-gray-700');
            activeTab.classList.add('bg-blue-600', 'text-white');

            document.querySelectorAll('.contract-options').forEach(option => {
                option.classList.add('hidden');
            });

            const currentOptions = document.getElementById(`${type}-options`);
            if (currentOptions) {
                currentOptions.classList.remove('hidden');
            }

            const titles = {
                commercial: 'Nouveau Contrat Commercial',
                accostage: 'Nouveau Contrat d\'Accostage',
                randonnee: 'Nouveau Contrat de Randonnée'
            };

            const listTitles = {
                commercial: 'Liste des Contrats Commerciaux',
                accostage: 'Liste des Contrats d\'Accostage',
                randonnee: 'Liste des Contrats de Randonnée'
            };

            document.getElementById('form-title').textContent = titles[type];
            document.getElementById('contract-list-title').textContent = listTitles[type];
            document.getElementById('contract-type').value = type;
        }

        function resetForm() {
            document.getElementById('contract-form').reset();
        }

        // Form submission handler
        document.getElementById('contract-form').addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Here you would typically send the form data via AJAX
            const formData = new FormData(this);
            
            // Example AJAX call (uncomment and modify as needed)
            /*
            fetch('/contracts', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Handle success
                    alert('Contrat créé avec succès!');
                    resetForm();
                    // Reload contracts list
                } else {
                    // Handle errors
                    alert('Erreur lors de la création du contrat');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Erreur de connexion');
            });
            */
            
            // For demo purposes
            alert('Contrat sauvegardé avec succès! (Mode démo)');
            resetForm();
        });
    </script>

    <style>
        .transition-colors {
            transition: background-color 0.2s ease-in-out, color 0.2s ease-in-out;
        }
        
        .hover\:bg-blue-700:hover {
            background-color: #1d4ed8;
        }
        
        .hover\:bg-gray-300:hover {
            background-color: #d1d5db;
        }
        
        .hover\:bg-green-700:hover {
            background-color: #15803d;
        }
        
        .focus\:ring-2:focus {
            box-shadow: 0 0 0 2px rgba(59, 130, 246, 0.5);
        }
        
        .focus\:border-transparent:focus {
            border-color: transparent;
        }
    </style>
</x-app-layout>