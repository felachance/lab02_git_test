<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-6xl text-gray-800 leading-tight">
                {{ __('Remplacements') }}
            </h2>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <!-- Header with status badge -->
                <div class="bg-gradient-to-r from-blue-50 to-indigo-50 border-b border-gray-200 px-6 py-5 rounded-t-lg">
                    <div class="flex flex-col md:flex-row md:justify-between md:items-center">
                        <div>
                            <h1 class="text-2xl font-bold text-gray-800">{{ $replacement->name }}</h1>
                            <div class="flex items-center text-gray-500 text-sm mt-2">
                                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                                <span>Créé le {{ $replacement->created_at->format('d/m/Y à H:i') }}</span>
                            </div>
                        </div>

                        @if(isset($replacement->status))
                        <div class="mt-3 md:mt-0">
                            @php
                                $statusColor = match($replacement->status) {
                                    'Acceptée' => 'bg-green-100 text-green-800',
                                    'En attente' => 'bg-yellow-100 text-yellow-800',
                                    'Expirée' => 'bg-red-100 text-red-800',
                                    default => 'bg-red-100 text-red-800'
                                };
                            @endphp
                            <span class="px-3 py-1.5 rounded-full text-sm font-medium {{ $statusColor }}">
                                {{ $replacement->status }}
                            </span>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Content -->
                <div class="p-6">
                    <!-- Description Card -->
                    <div class="mb-8">
                        <h3 class="text-sm font-medium text-gray-500 uppercase tracking-wider mb-3">Description</h3>
                        <div class="bg-gray-50 p-5 rounded-lg border border-gray-100">
                            <p class="text-gray-700">{{ $replacement->description }}</p>
                        </div>
                    </div>

                    <!-- Details Card -->
                    @if(isset($replacement->date) || isset($replacement->status) || isset($replacement->first_name) || isset($replacement->last_name) || isset($recentUser))
                    <div class="mb-8">
                        <h3 class="text-sm font-medium text-gray-500 uppercase tracking-wider mb-3">Détails supplémentaires</h3>
                        <div class="bg-gray-50 rounded-lg border border-gray-100">
                            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6 p-5">
                                @if(isset($replacement->date))
                                <div class="flex flex-col">
                                    <dt class="text-sm font-medium text-gray-600 mb-1">Date du remplacement</dt>
                                    <dd class="text-gray-800 font-medium">{{ $replacement->date }}</dd>
                                </div>
                                @endif

                                @if(isset($replacement->status))
                                <div class="flex flex-col">
                                    <dt class="text-sm font-medium text-gray-600 mb-1">Statut</dt>
                                    <dd class="text-gray-800 font-medium">{{ $replacement->status }}</dd>
                                </div>
                                @endif

                                @if(isset($replacement->first_name) && isset($replacement->last_name))
                                <div class="flex flex-col">
                                    <dt class="text-sm font-medium text-gray-600 mb-1">Utilisateur remplacé</dt>
                                    <dd class="flex items-center">
                                        <span class="inline-flex items-center justify-center h-8 w-8 rounded-full bg-blue-100 text-blue-500 mr-2">
                                            {{ substr($replacement->first_name, 0, 1) }}{{ substr($replacement->last_name, 0, 1) }}
                                        </span>
                                        <span class="text-gray-800 font-medium">{{ $replacement->first_name }} {{ $replacement->last_name }}</span>
                                    </dd>
                                </div>
                                @endif

                                @if(isset($recentUser))
                                <div class="flex flex-col">
                                    <dt class="text-sm font-medium text-gray-600 mb-1">Utilisateur assigné au quart</dt>
                                    <dd class="flex items-center">
                                        <span class="inline-flex items-center justify-center h-8 w-8 rounded-full bg-green-100 text-green-500 mr-2">
                                            {{ substr($recentUser->first_name, 0, 1) }}{{ substr($recentUser->last_name, 0, 1) }}
                                        </span>
                                        <span class="text-gray-800 font-medium">{{ $recentUser->first_name }} {{ $recentUser->last_name }}</span>
                                    </dd>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                    @endif

                    <!-- Actions -->
                    <div class="border-t border-gray-200 pt-6 mt-6">
                        <div class="flex flex-col sm:flex-row items-center justify-end space-y-3 sm:space-y-0 sm:space-x-4">
                            <a href="{{ route('replacements.index') }}" class="w-full sm:w-auto bg-gray-100 hover:bg-gray-200 text-gray-700 py-2.5 px-5 rounded-lg transition duration-150 ease-in-out flex items-center justify-center font-medium">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                                </svg>
                                Retour
                            </a>

                            <form method="POST" action="{{ route('replacements.destroy', $replacement) }}" class="w-full sm:w-auto">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                        class="w-full bg-red-500 hover:bg-red-600 text-white py-2.5 px-5 rounded-lg transition duration-150 ease-in-out flex items-center justify-center font-medium"
                                        onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce remplacement?')">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                    </svg>
                                    Supprimer
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
