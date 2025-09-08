<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-6xl text-gray-800 leading-tight">
            {{ __('Liste des succursales') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 space-y-6" x-data="{ showInactive: false }" >
                    <div class="flex items-center justify-between flex-wrap gap-4">
                        <a href="{{ route('branches.create') }}" class="inline-block bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 transition duration-200">
                            Ajouter une succursale
                        </a>
                        <div class="flex items-center space-x-4">
                            <label for="toggle-inactive" class="text-gray-700 font-medium">
                                Afficher les succursales inactives
                            </label>

                            <button
                                id="toggle-inactive"
                                @click="showInactive = !showInactive"
                                :class="showInactive ? 'bg-blue-600' : 'bg-gray-300'"
                                class="relative inline-flex h-6 w-11 items-center rounded-full transition-colors duration-300 focus:outline-none"
                            >
                                <span
                                    :class="showInactive ? 'translate-x-6' : 'translate-x-1'"
                                    class="inline-block w-4 h-4 transform bg-white rounded-full transition duration-300"
                                ></span>
                            </button>
                        </div>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="table-auto w-full text-left whitespace-no-wrap">
                            <thead>
                                <tr class="text-gray-600 uppercase text-sm leading-normal">
                                    <th class="py-3 px-6">Nom</th>
                                    <th class="py-3 px-6">Num&eacute;ro de rue</th>
                                    <th class="py-3 px-6">Rue</th>
                                    <th class="py-3 px-6">Ville</th>
                                    <th class="py-3 px-6"></th>
                                </tr>
                            </thead>

                            <tbody class="text-gray-700 text-sm font-light">
                                @foreach($branches as $branch)
                                    <tr x-show="{{ json_encode($branch->is_actif) }} || showInactive"
                                        class="border-b border-gray-200 hover:bg-gray-100">
                                        <td class="py-3 px-6">{{ $branch->name }}</td>
                                        <td class="py-3 px-6">{{ $branch->civic_no }}</td>
                                        <td class="py-3 px-6">{{ $branch->road }}</td>
                                        <td class="py-3 px-6">{{ $branch->city }}</td>
                                        <td class="py-3 px-6 space-x-2 flex">
                                            <a href="{{ route('branches.edit', $branch->id) }}"
                                               class="inline-block bg-blue-400 text-white px-3 py-1 rounded hover:bg-blue-500 transition duration-200">
                                                Modifier
                                            </a>

                                            <form action="{{ route('branches.destroy', $branch->id) }}" method="POST" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                        class="inline-block bg-red-500 text-white px-3 py-1 rounded hover:bg-red-600 transition duration-200"
                                                        onclick="return confirm('Are you sure?')">
                                                    Supprimer
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
