<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-6xl text-gray-800 leading-tight">
            {{ __('Remplacements') }}
        </h2>
    </x-slot>

    {{-- Alert Messages Section --}}
<div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-4 my-4">
    @if (session('error'))
        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded shadow-sm" role="alert">
            <div class="flex items-center">
                <svg class="h-5 w-5 text-red-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                </svg>
                <div>
                    <p>{{ session('error') }}</p>
                </div>
            </div>
        </div>
    @endif

    @if (session('success'))
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded shadow-sm" role="alert">
            <div class="flex items-center">
                <svg class="h-5 w-5 text-green-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                </svg>
                <p>{{ session('success') }}</p>
            </div>
        </div>
    @endif
</div>

{{-- Main Content Section --}}
<div class="py-6">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="">
            <div class="p-6" x-data>

                <div class="mb-6">
                    <h3 class="text-lg font-medium text-gray-700 mb-3">Filtres</h3>
                    <div class="flex flex-wrap gap-2" x-data="{ activeFilter: 'Tous' }">
                        <button
                            x-on:click="activeFilter = 'Tous'; window.fetchFilteredReplacement(undefined)"
                            x-bind:class="activeFilter === 'Tous' ? 'bg-blue-500 hover:bg-blue-600' : 'bg-gray-200 hover:bg-gray-300 text-gray-700'"
                            class="py-2 px-4 rounded-md transition duration-150 ease-in-out text-white">
                            Tous
                        </button>

                        <button
                            x-on:click="activeFilter = 'En attente'; window.fetchFilteredReplacement('En%20attente')"
                            x-bind:class="activeFilter === 'En attente' ? 'bg-blue-500 hover:bg-blue-600' : 'bg-gray-200 hover:bg-gray-300 text-gray-700'"
                            class="py-2 px-4 rounded-md transition duration-150 ease-in-out text-white">
                            En attente
                        </button>

                        <button
                            x-on:click="activeFilter = 'Acceptée'; window.fetchFilteredReplacement('Acceptée')"
                            x-bind:class="activeFilter === 'Acceptée' ? 'bg-blue-500 hover:bg-blue-600' : 'bg-gray-200 hover:bg-gray-300 text-gray-700'"
                            class="py-2 px-4 rounded-md transition duration-150 ease-in-out text-white">
                            Acceptée
                        </button>

                        <button
                            x-on:click="activeFilter = 'Expirée'; window.fetchFilteredReplacement('Expirée')"
                            x-bind:class="activeFilter === 'Expirée' ? 'bg-blue-500 hover:bg-blue-600' : 'bg-gray-200 hover:bg-gray-300 text-gray-700'"
                            class="py-2 px-4 rounded-md transition duration-150 ease-in-out text-white">
                            Expirée
                        </button>

                        <button
                            x-on:click="activeFilter = 'Annulée'; window.fetchFilteredReplacement('Annulée')"
                            x-bind:class="activeFilter === 'Annulée' ? 'bg-blue-500 hover:bg-blue-600' : 'bg-gray-200 hover:bg-gray-300 text-gray-700'"
                            class="py-2 px-4 rounded-md transition duration-150 ease-in-out text-white">
                            Annulée
                        </button>
                    </div>
                </div>

                <div class="divide-y divide-gray-200" id="replacements-list">
                    @include('replacements.replacements', ['replacements' => $replacements])
                </div>
            </div>
        </div>
    </div>
</div>
</x-app-layout>
