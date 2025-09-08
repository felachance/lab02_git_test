<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-6xl text-gray-800 leading-tight">
            {{ __('Congés') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="p-6 text-gray-900">
                <div class="bg-white shadow-md rounded-lg overflow-hidden">
                    @if($timeoffs->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="w-full">
                                <thead>
                                    <tr class="text-left bg-gray-100">
                                        <th class="px-4 py-3 font-semibold text-gray-700">Nom de l'employé</th>
                                        <th class="px-4 py-3 font-semibold text-gray-700">Demandé le</th>
                                        <th class="px-4 py-3 font-semibold text-gray-700">Dates</th>
                                        <th class="px-4 py-3 font-semibold text-gray-700">Heures</th>
                                        <th class="px-4 py-3 font-semibold text-gray-700">Statut</th>
                                        <th class="px-4 py-3 font-semibold text-gray-700 text-right">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200">
                                    @foreach($timeoffs as $timeoff)
                                        @php
                                            $status = $timeoff->type->name;
                                        @endphp
                                        <tr class="hover:bg-gray-50 transition-colors" x-data="{ status: '{{ $status }}', loading: false }">
                                            <td class="px-4 py-4 font-medium text-gray-800">
                                                {{ $timeoff->user->first_name }} {{ $timeoff->user->last_name }}
                                            </td>
                                            <td class="px-4 py-4 text-gray-700">
                                                {{ $timeoff->created_at->format('d/m/Y à H\hi') }}
                                            </td>
                                            <td class="px-4 py-4 text-gray-700">
                                                {{ $timeoff->date_start }} → {{ $timeoff->date_end }}
                                            </td>
                                            <td class="px-4 py-4 text-gray-700">
                                                {{ $timeoff->hour_start }} → {{ $timeoff->hour_end }}
                                            </td>
                                            <td class="px-4 py-4">
                                                <span id="timeoff-status-{{ $timeoff->id }}"
                                                    class="px-3 py-1 text-xs font-medium rounded-full inline-flex items-center"
                                                    :class="{
                                                        'bg-green-100 text-green-800': status === 'Approuvée',
                                                        'bg-yellow-100 text-yellow-800': status === 'En attente',
                                                        'bg-red-100 text-red-800': ['Expirée', 'Annulée', 'Refusée'].includes(status),
                                                        'bg-gray-100 text-gray-800': !['Approuvée', 'En attente', 'Expirée', 'Annulée', 'Refusée'].includes(status)
                                                    }"
                                                >
                                                    <template x-if="status === 'Approuvée'">
                                                        <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                                        </svg>
                                                    </template>
                                                    <template x-if="status === 'En attente'">
                                                        <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                                                        </svg>
                                                    </template>
                                                    <template x-if="['Expirée', 'Annulée', 'Refusée'].includes(status)">
                                                        <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                                                        </svg>
                                                    </template>
                                                    <span x-text="status"></span>
                                                </span>
                                            </td>
                                            <td class="px-4 py-4 text-right space-x-2 whitespace-nowrap" id="timeoff-buttons-{{ $timeoff->id }}">
                                                <template x-if="status === 'En attente'">
                                                    <div class="space-x-2">
                                                        <button
                                                            class="px-3 py-1.5 bg-green-500 hover:bg-green-600 text-white text-sm rounded-md"
                                                            x-bind:disabled="loading"
                                                            x-on:click="fetchUpdateStatus('Approuvée')({{ $timeoff->id }})"
                                                        >Accepter</button>

                                                        <button
                                                            class="px-3 py-1.5 bg-red-500 hover:bg-red-600 text-white text-sm rounded-md"
                                                            x-bind:disabled="loading"
                                                            x-on:click="if (confirm('Êtes-vous sûr de vouloir refuser cette demande ?')) {fetchUpdateStatus('Refusée')({{ $timeoff->id }})}"
                                                        >Refuser</button>
                                                    </div>
                                                </template>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="flex flex-col items-center justify-center py-12 bg-gray-50">
                            <div class="rounded-full bg-gray-100 p-3 mb-4">
                                <svg class="h-10 w-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                            </div>
                            <h3 class="text-lg font-medium text-gray-900">Aucun congé</h3>
                            <p class="text-gray-500 mt-1">Aucune demande à afficher pour le moment.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
