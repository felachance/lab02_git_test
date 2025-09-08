<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-6xl text-gray-800 leading-tight">
            {{ __('Liste des employés') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="p-6 text-gray-900">
                <!-- Filters and Search Bar -->
                <div class="mb-6">
                    <form method="POST" action="{{ route('employee.index') }}" class="flex flex-wrap items-center gap-4" x-data="fetchRechercheUser()" @submit.prevent="handleSubmit()">
                        @csrf
                        <!-- Search Bar -->
                        <div class="flex-grow">
                            <input type="text" name="research" value="{{ request('research') }}" placeholder="{{ __('Rechercher un employé...') }}"
                                class="w-full border-gray-300 rounded shadow-sm focus:ring focus:ring-blue-200 focus:border-blue-500"
                                x-model="fields.research">
                        </div>

                        <!-- Role Filter -->
                        <div>
                            <select name="role" class="border-gray-300 rounded shadow-sm focus:ring focus:ring-blue-200 focus:border-blue-500"
                                x-model="fields.role">
                                <option value="">{{ __('Tous les titres') }}</option>
                                @foreach ($roles as $role)
                                    <option value="{{ $role->id }}" {{ request('role') == $role->id ? 'selected' : '' }}>
                                        {{ $role->role }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <!-- Seniority Filter -->
                        <div>
                            <select name="seniority" class="border-gray-300 rounded shadow-sm focus:ring focus:ring-blue-200 focus:border-blue-500"
                                x-model="fields.seniority">
                                <option value="">{{ __('Trier par ancienneté') }}</option>
                                <option value="asc" {{ request('seniority') == 'asc' ? 'selected' : '' }}>{{ __('Ancienneté croissante') }}</option>
                                <option value="desc" {{ request('seniority') == 'desc' ? 'selected' : '' }}>{{ __('Ancienneté décroissante') }}</option>
                            </select>
                        </div>
                        <!-- Submit Button -->
                        <div>
                            <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white font-semibold py-2 px-4 rounded transition">
                                {{ __('Filtrer') }}
                            </button>
                        </div>
                    </form>
                </div>
                <!-- Employee Grid -->
                <div id="user-cards" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                    @foreach ($users as $user)
                        <div class="bg-white p-4 rounded shadow text-center" data-user-id="{{ $user->id }}">
                            <h3 class="text-xl font-semibold mb-2">{{ $user->first_name }} {{ $user->last_name }}</h3>
                            <p class="text-sm"><span class="font-semibold">Titre :</span> {{ $user->roles->pluck('role')->join(', ') }}</p>
                            <p class="text-sm mb-4"><span class="font-semibold">Code :</span> {{ $user->code }}</p>
                            <div class="flex justify-center gap-2">
                                <a href="{{ route('employee.edit', $user->code) }}"
                                    class="bg-blue-400 hover:bg-blue-500 text-white font-semibold py-1 px-3 rounded transition">
                                    {{ __('Détail') }}
                                </a>
                                <a href="{{ route('availability.index', $user->id) }}"
                                    class="bg-blue-400 hover:bg-blue-500 text-white font-semibold py-1 px-3 rounded transition">
                                    {{ __('Disponibilités') }}
                                </a>
                            </div>
                        </div>
                    @endforeach

                    <!-- No employee -->
                    @if ($users->isEmpty())
                        <div class="col-span-full text-center text-gray-500">
                            {{ __('Aucun employé présent.') }}
                        </div>
                    @endif
                </div>
                <!-- Add employee -->
                <div class="mb-6 mt-6">
                    <a href="{{ route('employee.create') }}" class="ml-4 bg-blue-500 hover:bg-blue-600 text-white font-semibold py-2 px-4 rounded transition float-right">
                        {{ __('Ajouter un employé') }}
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
