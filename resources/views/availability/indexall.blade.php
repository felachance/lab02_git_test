<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-6xl text-gray-800 leading-tight">
            {{ __('Disponibilités') }}
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
                <div x-data="{
                    availabilities: [],
                    searchName: '',
                    searchDay: '',
                    uniqueDays: [],

                    init() {
                        // Initialiser les données à partir du PHP
                        this.availabilities = [
                            @foreach($availabilities as $availability)
                                {
                                    firstName: '{{ $availability->first_name }}',
                                    lastName: '{{ $availability->last_name }}',
                                    fullName: '{{ $availability->first_name }} {{ $availability->last_name }}',
                                    dayName: '{{ $availability->day_name }}',
                                    startTime: '{{ $availability->start_time }}',
                                    endTime: '{{ $availability->end_time }}'
                                },
                            @endforeach
                        ];

                        // Extraire les jours uniques pour le filtre
                        this.uniqueDays = [...new Set(this.availabilities.map(item => item.dayName))];
                    },

                    filteredAvailabilities() {
                        return this.availabilities.filter(item => {
                            const nameMatch = item.fullName.toLowerCase().includes(this.searchName.toLowerCase());
                            const dayMatch = this.searchDay === '' || item.dayName === this.searchDay;
                            return nameMatch && dayMatch;
                        });
                    }
                }" class="p-4">
                    <div class="mb-6 flex flex-col md:flex-row gap-4">
                        <div class="w-full md:w-1/2">
                            <label for="searchName" class="block text-sm font-medium text-gray-700 mb-1">Filtrer par nom</label>
                            <input
                                type="text"
                                id="searchName"
                                x-model="searchName"
                                class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                placeholder="Entrez un nom..."
                            >
                        </div>

                        <div class="w-full md:w-1/2">
                            <label for="searchDay" class="block text-sm font-medium text-gray-700 mb-1">Filtrer par jour</label>
                            <select
                                id="searchDay"
                                x-model="searchDay"
                                class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                            >
                                <option value="">Tous les jours</option>
                                <template x-for="day in uniqueDays" :key="day">
                                    <option x-text="day" :value="day"></option>
                                </template>
                            </select>
                        </div>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="min-w-full bg-white rounded-lg shadow-md">
                            <thead>
                                <tr class="bg-gray-100 text-gray-700 uppercase text-sm">
                                    <th class="py-3 px-6 text-left">Nom</th>
                                    <th class="py-3 px-6 text-left">Jour</th>
                                    <th class="py-3 px-6 text-left">Heure de début</th>
                                    <th class="py-3 px-6 text-left">Heure de fin</th>
                                </tr>
                            </thead>
                            <tbody class="text-gray-600">
                                <template x-for="(item, index) in filteredAvailabilities()" :key="index">
                                    <tr class="border-b hover:bg-gray-50">
                                        <td class="py-3 px-6 text-left" x-text="item.fullName"></td>
                                        <td class="py-3 px-6 text-left" x-text="item.dayName"></td>
                                        <td class="py-3 px-6 text-left" x-text="item.startTime"></td>
                                        <td class="py-3 px-6 text-left" x-text="item.endTime"></td>
                                    </tr>
                                </template>
                                <template x-if="filteredAvailabilities().length === 0">
                                    <tr>
                                        <td colspan="4" class="py-6 text-center text-gray-500">Aucun résultat trouvé</td>
                                    </tr>
                                </template>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</x-app-layout>
