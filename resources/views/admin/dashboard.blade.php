<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" />
<title>Accueil - Benbar</title>
<script src="https://cdn.tailwindcss.com"></script>
<link href="https://fonts.googleapis.com/css2?family=Figtree:wght@300;400;500;600;700&display=swap" rel="stylesheet" />

<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Dashboard') }}
            </h2>
        </div>
    </x-slot>

    <!-- Animated Background Container -->
    <div class="fixed inset-0 overflow-hidden pointer-events-none z-0">
        <!-- Gradient Background -->
        {{-- <div class="absolute inset-0 bg-gradient-to-br from-blue-50 via-purple-50 to-pink-50 dark:bg-purple-900/20 transition-colors duration-500"></div> --}}
        <div className="absolute inset-0 bg-gradient-to-br from-blue-50 via-purple-50 to-pink-50 dark:bg-purple-950/20 transition-colors duration-500"></div>

    </div>

    <div class="relative z-10 py-10 px-4 sm:px-6 lg:px-8" id="dsq">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            @php
                $metrics = [
                    [
                        'label' => 'Total Demandes',
                        'count' => \App\Models\Demande::count(),
                        'color' => 'purple',
                        'icon' => 'fa-file-invoice'
                    ],
                    [
                        'label' => 'Budget Tables',
                        'count' => \App\Models\BudgetTable::count(),
                        'color' => 'blue',
                        'icon' => 'fa-table'
                    ],
                    [
                        'label' => 'Users',
                        'count' => \App\Models\User::count(),
                        'color' => 'green',
                        'icon' => 'fa-users'
                    ],
                ];
            @endphp

            @foreach($metrics as $metric)
                @php $color = $metric['color']; @endphp
                <div class="metric-card group relative bg-white/80 dark:bg-gray-800/80 backdrop-blur-sm border border-white/20 dark:border-gray-700/50 rounded-2xl shadow-xl hover:shadow-2xl p-6 overflow-hidden transition-all duration-300 hover:scale-105">
                    <!-- Card Glow Effect -->
                    <div class="absolute inset-0 bg-gradient-to-r from-{{ $color }}-500/10 to-{{ $color }}-600/10 opacity-0 group-hover:opacity-100 transition-opacity duration-300 rounded-2xl"></div>
                    
                    
                    <!-- Icon -->
                    <div class="flex items-center justify-between mb-4 relative z-10">
                        <div class="p-3 bg-{{ $color }}-100 dark:bg-{{ $color }}-900/50 rounded-xl group-hover:scale-110 transition-transform duration-300">
                            <i class="fas {{ $metric['icon'] }} text-{{ $color }}-600 dark:text-{{ $color }}-400 text-xl"></i>
                        </div>
                        <div class="text-{{ $color }}-500 opacity-20 text-6xl">
                            <i class="fas {{ $metric['icon'] }}"></i>
                        </div>
                    </div>

                    <h3 class="font-bold text-lg mb-2 relative z-10 text-gray-700 dark:text-gray-200">{{ $metric['label'] }}</h3>
                    <p class="text-4xl font-extrabold relative z-10 text-{{ $color }}-600 dark:text-{{ $color }}-400 group-hover:text-{{ $color }}-500 transition-colors duration-300">{{ $metric['count'] }}</p>
                    
                    <!-- Progress Bar Animation -->
                    <div class="absolute bottom-0 left-0 h-1 bg-{{ $color }}-500 rounded-b-2xl transform scale-x-0 group-hover:scale-x-100 transition-transform duration-500 origin-left"></div>
                </div>
            @endforeach
        </div>

        <div class="mt-10">
            <h2 class="text-2xl font-bold text-gray-800 dark:text-gray-100 mb-6 relative">
                <span class="bg-gradient-to-r from-purple-600 to-blue-600 bg-clip-text text-transparent">Quick Access</span>
                <div class="absolute -bottom-2 left-0 w-16 h-1 bg-gradient-to-r from-purple-500 to-blue-500 rounded-full"></div>
            </h2>
            
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-4">
                @php
                    $quickActions = [
                        ['route' => 'demande.add-demande', 'icon' => 'fa-plus', 'label' => 'Add Demande', 'color' => 'purple'],
                        ['route' => 'budget-tables.create', 'icon' => 'fa-square-plus', 'label' => 'Create Budget Table', 'color' => 'green'],
                        ['route' => 'budget-tables.index', 'icon' => 'fa-table', 'label' => 'View Budget Tables', 'color' => 'yellow'],
                        ['route' => 'admin.demandes', 'icon' => 'fa-list-check', 'label' => 'Manage Demandes', 'color' => 'pink'],
                        ['route' => 'acce.index', 'icon' => 'fa-users', 'label' => 'Manage Users', 'color' => 'blue']
                    ];
                @endphp

                @foreach($quickActions as $action)
                    <a href="{{ route($action['route']) }}"
                       class="quick-action-card group relative block p-6 bg-white/60 dark:bg-gray-800/60 backdrop-blur-md border border-white/30 dark:border-gray-700/30 rounded-2xl text-center hover:bg-{{ $action['color'] }}-50 dark:hover:bg-{{ $action['color'] }}-900/30 transition-all duration-300 hover:scale-105 hover:shadow-xl transform hover:-translate-y-1">
                        
                        <!-- Icon Container -->
                        <div class="mx-auto w-16 h-16 bg-{{ $action['color'] }}-100 dark:bg-{{ $action['color'] }}-900/50 rounded-2xl flex items-center justify-center mb-4 group-hover:scale-110 transition-transform duration-300 group-hover:rotate-3">
                            <i class="fas {{ $action['icon'] }} text-{{ $action['color'] }}-600 dark:text-{{ $action['color'] }}-400 text-2xl"></i>
                        </div>
                        
                        <!-- Label -->
                        <span class="text-sm font-semibold text-gray-700 dark:text-gray-200 group-hover:text-{{ $action['color'] }}-700 dark:group-hover:text-{{ $action['color'] }}-300 transition-colors duration-300">
                            {{ $action['label'] }}
                        </span>
                        
                        <!-- Hover Effect Line -->
                        <div class="absolute bottom-0 left-1/2 w-0 h-1 bg-{{ $action['color'] }}-500 group-hover:w-full group-hover:left-0 transition-all duration-300 rounded-t-full"></div>
                    </a>
                @endforeach
            </div>
        </div>
    </div>

    <style>
        * {
            font-family: 'Figtree', sans-serif;
        }
        /* Responsive adjustments */
        @media (max-width: 640px) {
            .particles-container .particle {
                display: none;
            }
            
            .animate-float-slow,
            .animate-float-delayed,
            .animate-float-reverse {
                animation-duration: 4s;
            }
        }
    </style>
</x-app-layout>